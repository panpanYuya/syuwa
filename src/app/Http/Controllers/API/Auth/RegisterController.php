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
            return response()->json([
                'flg' => $tmpUserFlg,
                'result' => 'success',
                'user' => $tmpUser,
            ]);
        }
        //TODO 仮登録されていない場合➀
        //TODO 仮登録を行うユーザーを登録する➀
        $tmpUserRegist= $this->registUserService->registTmpUser($tmpUser);

        //TODO 本登録用のメールを飛ばす➀
        //TODO 仮登録完了responseを返却する➀
        return response()->json([
            'result' => $tmpUserFlg,
            'user' => $tmpUserRegist,
        ]);

    }

    //TODO 仮登録のインスタンスに値をセットする関数を作成する
    public function setTmpUserRegistration(RegisterRequest $request): TmpUserRegistration
    {
        $tmpUserRegistration = new TmpUserRegistration();
        $tmpUserRegistration->user_name = $request->user_name;
        $tmpUserRegistration->email = $request->email;
        $tmpUserRegistration->password = $request->password;
        $tmpUserRegistration->birthday = $request->birthday;
        return $tmpUserRegistration;
    }


}
