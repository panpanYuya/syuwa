<?php

namespace App\Repositories;

use App\Models\Users\User;

interface RegistUserInterface
{
    public function createNewUser(User $user);
}
