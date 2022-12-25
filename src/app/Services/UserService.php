<?php

namespace App\Services;

use App\Models\FollowUser;
use App\Repositories\FollowUserRepository;
use App\Repositories\UserRepository;
use Exception;

class UserService
{
    private UserRepository $userRepository;
    private FollowUserRepository $followUserRepository;

    public function __construct(
        UserRepository $userRepository,
        FollowUserRepository $followUserRepository
    ) {
        $this->userRepository = $userRepository;
        $this->followUserRepository = $followUserRepository;
    }

    /**
     * 存在するユーザーを取得
     *
     * @param integer $userId
     * @return bool
     */
    public function existsUser(int $userId):bool
    {
        return $this->userRepository->existsUser($userId);
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
}
