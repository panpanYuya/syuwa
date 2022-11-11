<?php

namespace App\Repositories;

use App\Models\Users\TmpUserRegistration;

interface RegistTmpUserInterface
{
    public function createNewTmpUser(TmpUserRegistration $tmpUser);

    public function updateNewTmpUser(TmpUserRegistration $tmpUser);

    public function findTmpUser(string $token): TmpUserRegistration;

    public function checkTmpUser(string $email): bool;
}
