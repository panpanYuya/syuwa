<?php

namespace App\Repositories;

use App\Models\Users\TmpUserRegistration;

class RegistTmpUserRepository implements RegistTmpUserInterface
{
    /**
     * 仮登録ユーザーをDBに保存
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function createNewTmpUser(TmpUserRegistration $tmpUser)
    {
        $tmpUser->save();
    }

    /**
     * すでに仮登録されているユーザーを更新
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function updateNewTmpUser(TmpUserRegistration $tmpUser)
    {

        TmpUserRegistration::where('email', $tmpUser->email)
            ->update([

                'user_name' => $tmpUser->user_name,
                'email' => $tmpUser->email,
                'birthday' => $tmpUser->birthday,
                'token' => $tmpUser->token,
                'password' => $tmpUser->password,
            ]);
    }

    /**
     * 仮登録テーブルに登録済みのメールアドレスか確認
     *
     * @param string $email
     * @return boolean
     */
    public function checkTmpUser(string $email): bool
    {
        return TmpUserRegistration::where('email', $email)->exists();
    }

    /**
     * トークンに紐づく仮登録情報を取得
     *
     * @param string $token
     * @return TmpUserRegistration
     */
    public function findTmpUser(string $token): TmpUserRegistration
    {
        return TmpUserRegistration::where('token', $token)->firstOrFail();
    }
}
