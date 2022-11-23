<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Models\Users\TmpUserRegistration;
use App\Services\RegistUserService;
use Illuminate\Http\JsonResponse;


class RegisterController extends Controller
{

    private RegistUserService $registUserService;

    public function __construct(
        RegistUserService $registUserService
    ) {
        $this->registUserService = $registUserService;
    }

    public function registTmpUser(RegisterRequest $response): JsonResponse
    {
        //TODO 最後にTODOのコメントの消し忘れがないかを確認する
        $tmpUser = $this->setTmpUserRegistration($response);
        //入力されたユーザーが存在するかを確認する
        (bool) $tmpUserFlg = $this->registUserService->checkTmpUser($tmpUser->email);
        if ($tmpUserFlg) {
            $this->registUserService->updateTmpUser($tmpUser);
        } else {
            $this->registUserService->createTmpUser($tmpUser);
        }

        //TODO フロントエンド実装時に仮登録メールに記載するメールアドレスを追加する
        $this->registUserService->sendTemporaryMail($tmpUser->email, $tmpUser->user_name, $tmpUser->token);

        return response()->json([
            'result' => 'success'
        ], 200);
    }

    /**
     * 仮登録モデル型に変換
     *
     * @param RegisterRequest $request
     * @return TmpUserRegistration
     */
    public function setTmpUserRegistration(RegisterRequest $request): TmpUserRegistration
    {
        $tmpUserRegistration = new TmpUserRegistration();
        $tmpUserRegistration->user_name = $request->user_name;
        $tmpUserRegistration->email = $request->email;
        $tmpUserRegistration->password = $request->password;
        $tmpUserRegistration->birthday = $request->birthday;
        $tmpUserRegistration->token = $this->registUserService->createTmpToken();
        return $tmpUserRegistration;
    }
}
