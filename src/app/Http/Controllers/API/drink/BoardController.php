<?php

namespace App\Http\Controllers\API\drink;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BoardController extends Controller
{
    //

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
}
