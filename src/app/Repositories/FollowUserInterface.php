<?php

namespace App\Repositories;

use App\Models\FollowUser;

interface FollowUserInterface
{
    public function followUser(FollowUser $followUser): void;

    public function unfollowUser(int $userId, int $followedId): void;

    public function countFollowedUser(int $userId): int;

    public function countFolloweeUser(int $userId): int;

    public function followedByUserId(int $userId, int $checkUserId): bool;
}
