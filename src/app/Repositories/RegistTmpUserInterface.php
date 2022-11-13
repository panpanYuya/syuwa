<?php

namespace App\Repositories;

use App\Models\Users\TmpUserRegistration;

interface RegistTmpUserInterface
{
    public function createNewTmpUser(TmpUserRegistration $tmpUser);

    public function updateNewTmpUser(TmpUserRegistration $tmpUser);

    public function deleteTmpUser(TmpUserRegistration $tmpUser);

    public function checkTmpUser(string $email): bool;

    public function checkToken(string $token): bool;

    public function findTmpUserByToken(string $token): TmpUserRegistration;
}
