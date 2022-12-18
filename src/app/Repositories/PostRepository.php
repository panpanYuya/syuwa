<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository implements PostInterface
{

    /**
     * postIdから投稿の詳細情報を取得
     *
     * @param integer $postId
     * @return Post
     */
    public function getPostDetail(int $postId): Post
    {
        return Post::where('id', $postId)->with(['user', 'postTags', 'images', 'postTags.tag'])->first();
    }

}
