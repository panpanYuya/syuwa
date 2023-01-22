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
        $tmpUser = $this->setTmpUserRegistration($response);
        //入力されたユーザーが存在するかを確認する
        (bool) $tmpUserFlg = $this->registUserService->checkTmpUser($tmpUser->email);
        if ($tmpUserFlg) {
            $this->registUserService->updateTmpUser($tmpUser);
        } else {
            $this->registUserService->createTmpUser($tmpUser);
        }

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
        $tmpUserRegistration->password = $this->registUserService->hashedPassword($request->password);
        $tmpUserRegistration->birthday = $request->birthday;
        $tmpUserRegistration->token = $this->registUserService->createTmpToken();
        return $tmpUserRegistration;
    }
}
