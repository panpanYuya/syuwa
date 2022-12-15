<?php

namespace App\Repositories;

use App\Models\Image;
use App\Models\Post;
use App\Models\PostTag;

interface CreateNewPostInterface
{
    public function findLatestPost(int $userId);

    public function createPost(Post $post);

    public function createPostTag(PostTag $postTag);

    public function createImage(Image $image);

}
