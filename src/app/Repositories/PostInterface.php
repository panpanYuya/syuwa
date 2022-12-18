<?php

namespace App\Repositories;

use App\Models\Post;

interface PostInterface
{
    public function getPostDetail(int $postId):Post;

}
