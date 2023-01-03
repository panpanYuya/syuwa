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

    /**
     * ユーザーに紐づくすべての情報を取得
     *
     * @param integer $userId
     * @return User
     */
    public function findUserInfo(int $userId): User
    {
        return User::where('id',$userId)->with(['posts','posts.postTags', 'posts.images', 'posts.postTags.tag'])->first();
    }
}
