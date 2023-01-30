<?php

namespace App\Repositories;

use App\Models\Users\TmpUserRegistration;
use Illuminate\Database\Eloquent\Collection;

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
     * 仮登録テーブルに登録しているユーザーを削除
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function deleteTmpUser(TmpUserRegistration $tmpUser)
    {
        $tmpUser->delete();
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
     * 仮登録テーブルに同一のtokenが存在するか確認
     *
     * @param string $token
     * @return boolean
     */
    public function checkToken(string $token): bool
    {
        return TmpUserRegistration::where('token', $token)->exists();
    }

    /**
     * メールアドレスに紐づく仮登録ユーザー情報を取得
     *
     * @param string $email
     * @return Collection
     */
    public function findTmpUserByEmail(string $email): Collection
    {
        return TmpUserRegistration::where('email', $email)->get();
    }

    /**
     * トークンに紐づく仮登録情報を取得
     *
     * @param string $token
     * @return TmpUserRegistration
     */
    public function findTmpUserByToken(string $token): TmpUserRegistration
    {
        return TmpUserRegistration::where('token', $token)->firstOrFail();
    }

}
