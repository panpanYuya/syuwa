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
     * ユーザーの情報を取得
     *
     * @param integer $userId
     * @return User
     */
    public function findUser(int $userId): User
    {
        return $this->userRepository->findUser($userId);
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
     * ユーザーに紐づく情報を更新
     *
     * @param User $user
     * @return void
     */
    public function updateUser(User $user)
    {
        return $this->userRepository->updateUser($user);
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
