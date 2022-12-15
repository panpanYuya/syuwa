<?php

namespace App\Repositories;

use App\Models\Image;
use App\Models\Post;
use App\Models\PostTag;
use Illuminate\Support\Facades\DB;

class CreateNewPostRepository implements CreateNewPostInterface
{
    /**
     * ユーザーidに紐づく最新の投稿を取得する処理
     *
     * @param integer $userId
     * @return Post
     */
    public function findLatestPost(int $userId)
    {
        return DB::table('posts')->where('user_id', $userId)->latest('id')->first();
    }

    /**
     * 投稿テーブルにカラムを追加
     *
     * @param Post $post
     * @return void
     */
    public function createPost(Post $post)
    {
        $post->save();
    }

    /**
     * 投稿タグテーブルにカラムを追加
     *
     * @param PostTag $postTag
     * @return void
     */
    public function createPostTag(PostTag $postTag)
    {
        $postTag->save();
    }

    /**
     * 画像テーブルにカラムを追加
     *
     * @param Image $image
     * @return void
     */
    public function createImage(Image $image)
    {
        $image->save();
    }

}
