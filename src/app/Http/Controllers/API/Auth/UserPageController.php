<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\FollowUserService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserPageController extends Controller
{

    private UserService $userService;

    private FollowUserService $followUserService;

    public function __construct(
        UserService $userService,
        FollowUserService $followUserService
    ) {
        $this->userService = $userService;
        $this->followUserService = $followUserService;
    }

    /**
     * フォローテーブルにユーザーのフォロー情報を保存
     *
     * @param integer $userId
     * @return JsonResponse
     */
    public function showUserPage(int $showedUserId):JsonResponse
    {
        $followFlg = false;
        $userId = Auth::id();
        if ($userId != $showedUserId) {
            $followFlg = $this->followUserService->followedByUserId($userId, $showedUserId);
        }
        $numOfFollowed = $this->followUserService->countFollowedbyUserId($userId);
        $numOfFollowee = $this->followUserService->countFolloweeByUser($userId);
        $userInfo = $this->userService->findUserInfo($userId);
        return response()->json([
            'follow_flg' => $followFlg,
            'followed_num' => $numOfFollowed,
            'followee_num' => $numOfFollowee,
            'user_info' => $userInfo,
        ]);
    }
}
