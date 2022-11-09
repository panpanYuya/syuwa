<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Mail\RegistTmpUserMail;
use App\Models\Users\TmpUserRegistration;
use App\Services\RegistUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{

    private RegistUserService $registUserService;

    public function __construct(
        RegistUserService $registUserService
    ){
        $this->registUserService = $registUserService;
    }

    public function registUser(RegisterRequest $response): JsonResponse
    {
        // $user = $this.setUser($response);
        //TODO 最後にTODOのコメントの消し忘れがないかを確認する
        $tmpUser = $this->setTmpUserRegistration($response);
        //TODO 仮登録の判定を行い➀➁で動きを変える。
        //入力されたユーザーが存在するかを確認する
        (boolean) $tmpUserFlg =$this->registUserService->checkTmpUser($tmpUser->email);
        if($tmpUserFlg)
        {
            //TODO 仮登録を更新➁
            //TODO 仮登録のサービスで更新の関数を作成する
            //TODO 存在するユーザー情報を更新する処理を作成
            //TODO 仮登録のメールを送信する処理を記載
            $this->registUserService->updateTmpUser($tmpUser);
        } else{
            $this->registUserService->createTmpUser($tmpUser);
        }

        $registUrl = $this->registUserService->createRegistUrl($tmpUser->token);
        //TODO フロントエンド実装時に仮登録メールに記載するメールアドレスを追加する
        Mail::to($tmpUser->email)->send(new RegistTmpUserMail($tmpUser->user_name, $registUrl));

        return response()->json([
            'result' => 'success',
            'statasCode' => 200,
        ]);

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
