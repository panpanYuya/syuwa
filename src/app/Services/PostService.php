<?php

namespace App\Services;

use App\Consts\UrlConst;
use App\Consts\UtilConst;
use App\Mail\RegistTmpUserMail;
use App\Models\Post;
use App\Models\Image;
use App\Models\Post as ModelsPost;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use App\Repositories\RegistTmpUserRepository;
use App\Repositories\RegistUserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PostService
{
    public function addPost($userId, $tag, $imgUrl, $comment)
    {

        $postForm = $this->createPostForm($userId, $comment);
        DB::beginTransaction();
        try
        {
            $postForm->save();
            $resultPost = DB::table('posts')->where('user_id', $userId)->latest('id')->first();

            $tagForm = $this->createPostTagForm($resultPost->id, $tag);
            $tagForm->save();

            $imgForm = $this->createImgForm($resultPost->id, $imgUrl);
            $imgForm->save();

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
