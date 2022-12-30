<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostInterface
{
    public function searchPosts(int $numOfDisplaiedPosts):Collection;

    public function searchPostsByFollowee(int $userId, int $numOfDisplaiedPosts):Collection;

    public function getPostDetail(int $postId):Post;

}
