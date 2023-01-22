<?php

namespace App\Services;

use App\Models\FollowUser;
use App\Repositories\FollowUserRepository;
use Exception;

class FollowUserService
{
    private FollowUserRepository $followUserRepository;

    public function __construct(
        FollowUserRepository $followUserRepository
    ) {
        $this->followUserRepository = $followUserRepository;
    }


    /**
     * ユーザーがフォローをしているか判定
     *
     * @param integer $userId
     * @return boolean
     */
    public function isFollowedUser(int $userId)
    {
        (int) $numOfFollowee = $this->followUserRepository->countFollowedUser($userId);

        if ($numOfFollowee > 0) {
            return true;
        }
        return false;
    }

    /**
     * ユーザーIDに紐づくユーザーのフォローしているユーザー人数を返す
     *
     * @param integer $userId
     * @return integer
     */
    public function countFollowedbyUserId(int $userId): int
    {
        return $this->followUserRepository->countFollowedUser($userId);
    }

    /**
     * ユーザーIDに紐づくユーザーをフォローしているユーザー数を返す
     *
     * @param integer $userId
     * @return integer
     */
    public function countFolloweeByUser(int $userId): int
    {
        return $this->followUserRepository->countFolloweeUser($userId);
    }

    /**
     * userIdに紐づくユーザーがfollowedIdのユーザーをフォローしているのかを確認
     *
     * @param integer $userId
     * @param integer $followedId
     * @return integer
     */
    public function followedByUserId(int $userId, int $checkUserId): bool
    {
        return $this->followUserRepository->followedByUserId($userId, $checkUserId);
    }

    /**
     * フォローテーブルにユーザーのフォロー情報を保存
     *
     * @param FollowUser $followUser
     * @return void
     */
    public function followUser(FollowUser $followUser)
    {
        try {
            $this->followUserRepository->followUser($followUser);
        } catch (Exception $e) {
            abort(500);
        }
    }

    /**
     * フォローテーブルに存在しているフォロー情報を削除
     *
     * @param integer $userId
     * @param integer $followedId
     * @return void
     */
    public function unfollowUser(int $userId, int $followedId)
    {
        try {
            $this->followUserRepository->unfollowUser($userId, $followedId);
        } catch (Exception $e) {
            abort(500);
        }
    }
}
