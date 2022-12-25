<?php

namespace App\Repositories;

use App\Models\Users\User;

class UserRepository implements UserInterface
{
    /**
     * 該当のuserIdが存在しているか確認
     *
     * @param User $user
     * @return bool
     */
    public function existsUser(int $userId):bool
    {
        return User::find($userId)->exists();
    }
}
