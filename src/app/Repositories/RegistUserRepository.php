<?php

namespace App\Repositories;

use App\Models\Users\User;

class RegistUserRepository implements RegistUserInterface
{
    /**
     * ユーザーテーブルに登録する処理
     *
     * @param User $user
     * @return void
     */
    public function createNewUser(User $user)
    {
        $user->save();
    }
}
