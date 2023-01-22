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
    public function followUser(FollowUser $followUser): void
    {
        $followUser->save();
    }

    /**
     * フォローしていたユーザーのフォローを解除
     *
     * @param integer $followingId
     * @param integer $followedId
     * @return void
     */
    public function unfollowUser(int $userId, int $followedId): void
    {
        FollowUser::Where([
            ['following_id', $userId],
            ['followed_id', $followedId]
        ])->delete();
    }

    /**
     * ユーザーIDに紐づくユーザーがフォローしているユーザー数を返す
     *
     * @param integer $userId
     * @return integer $numOfFollowee
     */
    public function countFollowedUser(int $userId): int
    {
        return FollowUser::where('followed_id', $userId)->count();
    }

    /**
     * ユーザーIDに紐づくユーザーをフォローしているユーザー数を返す
     *
     * @param integer $userId
     * @return integer
     */
    public function countFolloweeUser(int $userId): int
    {
        return FollowUser::where('following_id', $userId)->count();
    }

    /**
     * ユーザーIDのユーザーがcheckUserIdのユーザーをフォローしているか確認
     *
     * @param integer $userId
     * @param integer $checkUserId
     * @return boolean
     */
    public function followedByUserId(int $userId, int $checkUserId): bool
    {
        return FollowUser::Where([
            ['following_id', $userId],
            ['followed_id', $checkUserId]
        ])->exists();
    }
}
