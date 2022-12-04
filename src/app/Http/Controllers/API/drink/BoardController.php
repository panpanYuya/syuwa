<?php

namespace App\Http\Controllers\API\drink;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Drink\PostAddRequest;
use App\Models\Post;
use App\Models\Users\User;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        if($followerFlg){

        } else{
            //フォローしているユーザーが存在していない場合は最新の投稿から10件を取得
            $posted = Post::orderBy('created_at', 'desc')->with(['postTags','images', 'postTags.tag'])->take(10)->get();
        }

        return response()->json([
            'post'=> $posted,
        ]);
    }

    public function add(PostAddRequest $postAddRequest): JsonResponse
    {
        //TODO 画像のbase64化をする
        //TODO Requestクラスから取得した値を専用のモデルに格納する
        // $userId = Auth::id();
        $userId = 1;
        $this->postService->addPost($userId, $postAddRequest->tag, $postAddRequest->img, $postAddRequest->comment);

        //TODO
        //TODO 格納した値が正しく格納できた場合は登録した物を追加するを返す。

        return response()->json([
            'result1' => true,
        ]);
    }
}
