<?php

namespace App\Repositories;

use App\Models\Users\TmpUserRegistration;

interface RegistTmpUserInterface
{
    public function registNewTmpUser(): TmpUserRegistration;

    public function checkTmpUser(string $email): bool;
}
