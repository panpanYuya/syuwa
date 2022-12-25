<?php

namespace App\Repositories;

interface UserInterface
{
    public function existsUser(int $userId): bool;
}
