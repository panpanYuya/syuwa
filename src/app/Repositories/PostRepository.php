<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostRepository implements PostInterface
{
    /**
     * 未表示の日付が新しい投稿を10件取得
     *
     * @param integer $numOfDisplaiedPosts(表示している投稿件数)
     * @return Collection
     */
    public function searchPosts(int $numOfDisplaiedPosts): Collection
    {
        return Post::orderBy('created_at', 'desc')->with(['postTags', 'images', 'postTags.tag'])->offset($numOfDisplaiedPosts)->limit(10)->get();
    }

    /**
     * フォローしているユーザーの未表示で日付が新しい投稿を10件取得
     *
     * @param integer $userId
     * @param integer $numOfDisplaiedPosts
     * @return Collection
     */
    public function searchPostsByFollowee(int $userId, int $numOfDisplaiedPosts): Collection
    {
        return Post::join('follow_users', 'user_id', '=', 'follow_users.following_id')
        ->where('follow_users.followed_id', $userId)
        ->select('posts.*', 'follow_users.following_id', 'follow_users.followed_id')
        ->with(['postTags', 'images', 'postTags.tag'])->offset($numOfDisplaiedPosts)->limit(10)->get();
    }

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
