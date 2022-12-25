<?php

namespace App\Repositories;

use App\Models\FollowUser;

class FollowUserRepository implements FollowUserInterface
{
    /**
     * フォローテーブルにユーザーのフォロー情報を保存
     *
     * @param FollowUser $followUser
     * @return void
     */
    public function followUser(FollowUser $followUser)
    {
        $followUser->save();
    }

}
