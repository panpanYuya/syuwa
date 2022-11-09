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
     * すでに仮登録されているユーザーを更新する処理
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function updateNewTmpUser(TmpUserRegistration $tmpUser)
    {
        //TODO ここでエラーが発生しているので、次はここから修正
        $registedTmpUser = TmpUserRegistration::where('email', $tmpUser)->get();
        $registedTmpUser->user_name = $tmpUser->user_name;
        $registedTmpUser->email = $tmpUser->email;
        $registedTmpUser->birthday = $tmpUser->birthday;
        $registedTmpUser->token = $tmpUser->token;
        $registedTmpUser->password = $tmpUser->password;
        // $this->setUpdatedTmpUser($registedTmpUser, $tmpUser);
        $registedTmpUser->save();
    }

    /**
     * 仮登録テーブルに登録済みのメールアドレスか確認
     *
     * @param string $email
     * @return boolean
     */
    public function checkTmpUser(string $email):bool
    {
        return TmpUserRegistration::where('email', $email)->exists();
    }

    /**
     * 登録済みの仮登録情報を上書きする処理
     *
     * @param TmpUserRegistration $registedTmpUser すでに登録済みの仮登録情報
     * @param TmpUserRegistration $registNewTmpUser 更新する仮登録情報
     * @return TmpUserRegistration
     */
    // public function setUpdatedTmpUser(TmpUserRegistration $registedTmpUser, TmpUserRegistration $registNewTmpUser): TmpUserRegistration
    // {
    //     $registedTmpUser->user_name = $registNewTmpUser->user_name;
    //     $registedTmpUser->email = $registNewTmpUser->email;
    //     $registedTmpUser->birthday = $registNewTmpUser->birthday;
    //     $registedTmpUser->token = $registNewTmpUser->token;
    //     $registedTmpUser->password = $registNewTmpUser->password;
    //     return $registedTmpUser;
    // }
}
