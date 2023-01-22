<?php

namespace App\Repositories;

use App\Models\Users\User;

interface UserInterface
{
    public function existsUser(int $userId): bool;

    public function findUserInfo(int $userId): User;

    public function findUserByEmail(string $email):User;

    public function updateUser(User $user);
}
