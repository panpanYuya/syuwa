<?php

namespace App\Services;


use App\Models\Post;
use App\Models\Image;
use App\Models\PostTag;
use App\Repositories\CreateNewPostRepository;
use App\Repositories\RegistTmpUserRepository;
use App\Repositories\RegistUserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PostService
{
    private CreateNewPostRepository $createNewPostRepository;

    public function __construct(
        CreateNewPostRepository $createNewPostRepository
    ) {
        $this->createNewPostRepository = $createNewPostRepository;
    }

    /**
     * 新規投稿を行う処理
     *
     * @param integer $userId
     * @param integer $tag
     * @param string $imgUrl
     * @param string $comment
     * @return void
     */
    public function addPost(int $userId, int $tag, string $imgUrl, string $comment)
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
    }

    /**
     * Base64でエンコードされた値を元のファイル形式に戻す
     *
     * @param string $encStr
     */
    public function decodeBase64(string $encStr)
    {
        return base64_decode($encStr);
    }

    public function toFile(string $encStr):array
    {
        list($fileInfo, $fileData) = explode(';', $encStr);
        // 拡張子を取得
        $extension = explode('/', $fileInfo)[1];
        // $fileDataにある"base64,"を削除する
        list(, $fileData) = explode(',', $fileData);
        // base64をデコード
        $fileData = base64_decode($fileData);
        // ランダムなファイル名生成
        $fileName = md5(uniqid(rand(), true)) . ".$extension";

        return array($fileName, $fileData);

    }

    /**
     * Undocumented function
     *
     * @param string $fileName
     * @param string $fileData
     * @return string $storeUrl
     */
    public function storePhoto(string $fileName, string $fileData): string
    {
        try {
            Storage::disk('s3')->put($fileName, $fileData, 'public');
        } catch (Exception $e) {
            abort(500);
        }
        // データベースに保存するためのパスを返す
        return Storage::disk('s3')->url("/syuwa-post-img/$fileName");

    }

    /**
     * 投稿テーブルに登録用のModelを作成
     *
     * @param integer $userId
     * @param string $comment
     * @return Post
     */
    private function createPostForm(int $userId, string $comment): Post
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
    private function createPostTagForm(int $postId, int $tag): PostTag
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
    private function createImgForm(int $postId, string $imgUrl): Image
    {
        $img = new Image();
        $img->post_id = $postId;
        $img->img_url = $imgUrl;

        return $img;
    }

}
