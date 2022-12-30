<?php

namespace App\Http\Controllers\API\drink;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Drink\PostAddRequest;
use App\Services\FollowUserService;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{

    private PostService $postService;

    private FollowUserService $followUserService;

    public function __construct(
        PostService $postService,
        FollowUserService $followUserService
    ) {
        $this->postService = $postService;
        $this->followUserService = $followUserService;
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
}
