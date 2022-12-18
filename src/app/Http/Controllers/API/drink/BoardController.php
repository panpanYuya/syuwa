<?php

namespace App\Http\Controllers\API\drink;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Drink\PostAddRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{

    private PostService $postService;

    public function __construct(
        PostService $postService
    ) {
        $this->postService = $postService;
    }

    /**
     * 投稿一覧画面に必要な情報を返す
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        //TODO フォロー機能が作成後に修正する
        $followerFlg = false;

        //フォローしているユーザーが存在している場合はフォローユーザーの投稿から最新の10件を取得する
        if ($followerFlg) {
        } else {
            //フォローしているユーザーが存在していない場合は最新の投稿から10件を取得
            $posted = Post::orderBy('created_at', 'desc')->with(['postTags', 'images', 'postTags.tag'])->take(10)->get();
        }

        return response()->json([
            'post' => $posted,
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
