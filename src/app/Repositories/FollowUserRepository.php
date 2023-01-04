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
     * ユーザーIDに紐づくユーザーがフォローしているユーザー数を返す
     *
     * @param integer $userId
     * @return integer $numOfFollowee
     */
    public function countFollowedUser(int $userId):int
    {
        return FollowUser::where('followed_id', $userId)->count();
    }

    /**
     * ユーザーIDに紐づくユーザーをフォローしているユーザー数を返す
     *
     * @param integer $userId
     * @return integer
     */
    public function countFolloweeUser(int $userId):int
    {
        return FollowUser::where('following_id', $userId)->count();
    }

    /**
     * ユーザーIDのユーザーがfollowedIdのユーザーをフォローしているか確認
     *
     * @param integer $userId
     * @param integer $followedId
     * @return boolean
     */
    public function followedByUserId(int $userId, int $followedId):bool
    {
        return FollowUser::Where([
            ['following_id', $followedId],
            ['followed_id', $userId]
        ])->exists();
    }

}
