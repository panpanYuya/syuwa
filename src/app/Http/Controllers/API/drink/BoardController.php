<?php

namespace App\Http\Controllers\API\drink;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Drink\PostAddRequest;
use App\Models\Post;
use App\Services\FollowUserService;
use App\Services\PostService;
use App\Services\PostTagService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{

    private PostService $postService;

    private FollowUserService $followUserService;

    private PostTagService $postTagService;

    public function __construct(
        PostService $postService,
        FollowUserService $followUserService,
        PostTagService $postTagService
    ) {
        $this->postService = $postService;
        $this->followUserService = $followUserService;
        $this->postTagService = $postTagService;
    }

    /**
     * 投稿一覧画面に必要な情報を返す
     *
     * @param integer $numOfDisplaiedPosts
     * @return JsonResponse
     */
    public function show(int $numOfDisplaiedPosts): JsonResponse
    {
        $userId = Auth::id();

        $isFollowing = $this->followUserService->isFollowedUser($userId);

        //フォローしているユーザーが存在している場合はフォローユーザーの投稿から最新の10件を取得する
        if ($isFollowing) {
            $posts = $this->postService->searchPostsByFollowee($userId, $numOfDisplaiedPosts);
        } else {
            $posts = $this->postService->searchPosts($numOfDisplaiedPosts);
        }

        return response()->json([
            'post' => $posts
        ]);
    }

    /**
     * 登録されているタグを取得
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        //TODO タグを取得する処理をサービスクラスに追加する
        $tags = $this->postService->getTags();
        return response()->json([
            'tags' => $tags,
        ]);
    }

    /**
     * 投稿を登録する処理
     * 画像はS3に画像ファイルとして保存
     *
     * @param PostAddRequest $postAddRequest
     * @return JsonResponse
     */
    public function add(PostAddRequest $postAddRequest): JsonResponse
    {
        $userId = Auth::id();
        list($fileName, $fileData) = $this->postService->base64ToFile($postAddRequest->post_image);
        $storeUrl = $this->postService->storePhoto($fileName, $fileData);
        $this->postService->addPost($userId, $postAddRequest->post_tag, $storeUrl, $postAddRequest->comment);

        return response()->json([
            'result' => true,
        ]);
    }


    public function detail(int $postId): JsonResponse
    {
        $postDetail = $this->postService->getPostDetail($postId);

        return response()->json([
            'post' => $postDetail,
        ]);
    }

    /**
     * タグから投稿を取得
     *
     * @param integer $tagId
     * @param integer $numOfDisplaiedPosts
     * @return JsonResponse
     */
    public function searchPostsByTag(int $tagId, int $numOfDisplaiedPosts): JsonResponse
    {
        $result = $this->postTagService->sortPostsByTag($tagId, $numOfDisplaiedPosts);
        $posts = $this->postResponseForm($result);
        return response()->json([
            'post' => $posts,
        ]);

    }

    /**
     * Collection型を投稿一覧機能のResponseと同じ形に変形
     *
     * @param Collection $postTagsResult
     * @return array
     */
    private function postResponseForm(Collection $postTagsResult):array
    {
        $posts = [];

        for ($i =0; $i < count($postTagsResult); $i++) {
            $post = new Post();

            //Postモデルに情報を格納
            $post->id = $postTagsResult[$i]->post->id;
            $post->user_id = $postTagsResult[$i]->post->user_id;
            $post->text = $postTagsResult[$i]->post->text;
            $post->created_at = $postTagsResult[$i]->post->created_at;

            //PostモデルのPostTagモデルに情報を格納
            $post->postTags[0]->id = $postTagsResult[$i]->id;
            $post->postTags[0]->tag_id = $postTagsResult[$i]->tag_id;

            //PostモデルのTagモデルに情報を格納
            $post->postTags[0]->tag->id = $postTagsResult[$i]->tag->id;
            $post->postTags[0]->tag->tag_name = $postTagsResult[$i]->tag->tag_name;

            //PostモデルのImageモデルに情報を格納
            $post->images[0]->id= $postTagsResult[$i]->post->images[0]->id;
            $post->images[0]->post_id= $postTagsResult[$i]->post->images[0]->post_id;
            $post->images[0]->img_url= $postTagsResult[$i]->post->images[0]->img_url;

            array_push($posts, $post);
        }

        return $posts;
    }
}
