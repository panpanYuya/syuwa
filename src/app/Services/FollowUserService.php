<?php

namespace App\Services;

use App\Repositories\FollowUserRepository;

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
        (int) $numOfFollowee = $this->followUserRepository->isFollowedUser($userId);

        if ($numOfFollowee > 0) {
            return true;
        }
        return false;

    }
}
