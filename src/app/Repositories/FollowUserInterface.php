<?php

namespace App\Repositories;

use App\Models\FollowUser;

interface FollowUserInterface
{
    public function followUser(FollowUser $followUser);

    public function isFollowedUser(int $userId): int;
}
