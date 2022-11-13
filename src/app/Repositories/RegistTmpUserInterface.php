<?php

namespace App\Repositories;

use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;

interface RegistTmpUserInterface
{
    public function createNewTmpUser(TmpUserRegistration $tmpUser);

    public function updateNewTmpUser(TmpUserRegistration $tmpUser);

    public function createNewUser(User $user);

    public function checkTmpUser(string $email): bool;

    public function findTmpUserByToken(string $token): TmpUserRegistration;
}
