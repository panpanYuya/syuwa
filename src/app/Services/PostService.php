<?php

namespace App\Services;


use App\Models\Post;
use App\Models\Image;
use App\Models\PostTag;
use App\Repositories\CreateNewPostRepository;
use App\Repositories\RegistTmpUserRepository;
use App\Repositories\RegistUserRepository;

use Illuminate\Support\Facades\DB;
use Throwable;

class PostService
{
    private CreateNewPostRepository $createNewPostRepository;

    public function __construct(
        CreateNewPostRepository $createNewPostRepository
    ) {
        $this->createNewPostRepository = $createNewPostRepository;
    }

    public function addPost($userId, $tag, $imgUrl, $comment)
    {

        $postForm = $this->createPostForm($userId, $comment);
        DB::beginTransaction();
        try
        {
            $this->createNewPostRepository->createPost($postForm);
            $latestPost = $this->createNewPostRepository->findLatestPost($userId);

            $postTagForm = $this->createPostTagForm($latestPost->id, $tag);
            $this->createNewPostRepository->createPostTag($postTagForm);

            $imgForm = $this->createImgForm($latestPost->id, $imgUrl);
            $this->createNewPostRepository->createImage($imgForm);

            DB::commit();
        } catch (Throwable $e)
        {
            DB::rollback();
            abort(500);
        }
        //TODO 画像テーブルに投稿を保存する処理
        //TODO AWSを導入後、S3に登録する処理を追加

    }

    /**
     * 投稿テーブルに登録用のModelを作成
     *
     * @param integer $userId
     * @param string $comment
     * @return Post
     */
    public function createPostForm(int $userId, string $comment): Post
    {
        $post = new Post();
        $post->user_id = $userId;
        $post->text = $comment;

        return $post;
    }

    /**
     * 投稿タグテーブルに登録用のModelを作成
     *
     * @param integer $postId
     * @param integer $tag
     * @return PostTag
     */
    public function createPostTagForm(int $postId, int $tag): PostTag
    {
        $postTag = new PostTag();
        $postTag->post_id = $postId;
        $postTag->tag_id = $tag;

        return $postTag;
    }

    /**
     * 画像登録に登録用のModelを作成
     *
     * @param integer $postId
     * @param string $imgUrl
     * @return Image
     */
    public function createImgForm(int $postId, string $imgUrl): Image
    {
        $img = new Image();
        $img->post_id = $postId;
        $img->img_url = $imgUrl;

        return $img;
    }

}
