<?php

namespace App\Services;


use App\Models\Post;
use App\Models\Image;
use App\Models\PostTag;
use App\Models\Tag;
use App\Repositories\CreateNewPostRepository;
use App\Repositories\PostRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PostService
{
    private CreateNewPostRepository $createNewPostRepository;

    private PostRepository $postRepository;

    public function __construct(
        CreateNewPostRepository $createNewPostRepository,
        PostRepository $postRepository
    ) {
        $this->createNewPostRepository = $createNewPostRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * タグを取得するメソッド
     *
     */
    public function getTags()
    {
        try {
            $tags = Tag::all();
        } catch (Throwable $e) {
            abort(500);
        }

        return  $tags;
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
        try {
            $this->createNewPostRepository->createPost($postForm);
            $latestPost = $this->createNewPostRepository->findLatestPost($userId);

            $postTagForm = $this->createPostTagForm($latestPost->id, $tag);
            $this->createNewPostRepository->createPostTag($postTagForm);

            $imgForm = $this->createImgForm($latestPost->id, $imgUrl);
            $this->createNewPostRepository->createImage($imgForm);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            abort(500);
        }
    }

    /**
     * base64型の文字列から画像をデコードし、新たなファイル名とデコードしたファイルを返す処理
     *
     * @param string $encStr
     * @return array ($fileName, $fileData)
     */
    public function base64ToFile(string $encStr): array
    {
        list($fileInfo, $fileData) = explode(';', $encStr);
        // 拡張子を取得
        $extension = explode('/', $fileInfo)[1];
        // $fileDataにある"base64,"を削除する
        list(, $fileData) = explode(',', $fileData);
        // base64をデコード
        $fileData = base64_decode($fileData);
        // ランダムなファイル名生成(uniqidの第二引数($more_entropy)をtrueにしているので23文字までしか生成されない)
        $fileName = md5(uniqid(rand(), true)) . ".$extension";

        return array($fileName, $fileData);
    }

    /**
     * 画像をS3に登録する処理
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
        //TODO urlの箇所をピリオドでつなげる
        return Storage::disk('s3')->url("/syuwa-post-img/$fileName");
    }

    /**
     * postIdに紐づく投稿詳細を取得する機能
     *
     * @param integer $postId
     * @return Post
     */
    public function getPostDetail(int $postId): Post
    {
        try {
            $postDetail = $this->postRepository->getPostDetail($postId);
        } catch (Exception $e)
        {
            abort(500);
        }
        return $postDetail;
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
