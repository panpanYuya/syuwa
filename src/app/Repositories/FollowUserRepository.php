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

    /**
     * フォローしているユーザーが何人存在するのか確認
     *
     * @param integer $userId
     * @return integer $numOfFollowee
     */
    public function isFollowedUser(int $userId):int
    {
        return FollowUser::where('followed_id', $userId)->count();
    }

}
