<?php

namespace App\Services;

use App\Models\Users\User;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * 存在するユーザーを取得
     *
     * @param integer $userId
     * @return bool
     */
    public function existsUser(int $userId): bool
    {
        return $this->userRepository->existsUser($userId);
    }


    /**
     * ユーザーに紐づくすべての情報を取得
     *
     * @param integer $userId
     * @return User
     */
    public function findUserInfo(int $userId): User
    {
        return $this->userRepository->findUserInfo($userId);
    }


    /**
     * emailアドレスに紐づくユーザーが存在しているのかを確認
     *
     * @param string $email
     * @return User
     */
    public function findUserByEmail(string $email): User
    {
        return $this->userRepository->findUserByEmail($email);
    }
}
