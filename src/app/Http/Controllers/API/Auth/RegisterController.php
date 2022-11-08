<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\RegistUserService;
use App\Models\Users\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{

    private RegistUserService $registUserService;

    public function __construct(
        RegistUserService $registUserService
    ){
        $this->registUserService = $registUserService;
    }

    public function registUser(JsonResponse $response): JsonResponse
    {
        // $user = $this.setUser($response);
        //TODO 最後にTODOのコメントの消し忘れがないかを確認する
        //TODO 入力された値をフォームに設定する➀
        //入力されたユーザーが存在するかを確認する
        $email = "test@test.com";
        //TODO 仮登録の判定を行い➀➁で動きを変える。
        (boolean) $tmpUserFlg =$this->registUserService->checkTmpUser($email);
        if($tmpUserFlg)
        {
            //TODO 仮登録を更新➁
            return response()->json([
                'flg' => $tmpUserFlg,
                'result' => 'success',
            ]);
        }
        //TODO 仮登録されていない場合➀
        //TODO 仮登録を行うユーザーを登録する➀

        //TODO 本登録用のメールを飛ばす➀
        //TODO 仮登録完了responseを返却する➀
        return response()->json([
            'result' => $tmpUserFlg,
        ]);

    }

    //TODO 仮登録のインスタンスに値をセットする関数を作成する
    public function setUser($request): User
    {
       $user = new User();
       $user->user_name = $request->user_name;
       $user->password = $request->password;
       return $user;
    }
}
