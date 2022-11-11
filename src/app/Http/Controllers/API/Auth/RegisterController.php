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

        $registUrl = $this->registUserService->createRegistUrl($tmpUser->token);
        //TODO フロントエンド実装時に仮登録メールに記載するメールアドレスを追加する
        Mail::to($tmpUser->email)->send(new RegistTmpUserMail($tmpUser->user_name, $registUrl));

        return response()->json([
            'result' => 'success'
        ], 200);
    }

    public function registUserComplete(): JsonResponse
    {

        $tmpUser = $this->registUserService->createRegistUser(request('token'));
        //TODO 登録から24時間超えてしまっている場合に登録されている物を削除するメソッド
        if($this->registUserService->checkExpirationDate($tmpUser->updated_at))
        {
            return response()->json([
                'result' => $tmpUser,
                'expire' => true,
            ], 200);
        }
        //TODO 個人情報のモデルを追加
        //TOOD 個人情報のmigrationを追加
        //TODO 個人情報のseederを追加
        //TODO Tmpテーブルから誕生日以外をユーザー情報テーブルに登録する処理
        //TODO tmpテーブルから誕生日をユーザー個人情報テーブルに追加
        //TODO try catchの処理をお追加
        //TODO 成功時にtmp情報を削除
        return response()->json([
            'result' => $tmpUser,
            'expire' => false,
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
        //TODO tokenを作成する時に登録されていないかを確認する
        $tmpUserRegistration->token = $this->registUserService->createTmpToken();
        return $tmpUserRegistration;
    }
}
