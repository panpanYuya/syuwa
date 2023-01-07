<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\FollowUser;
use App\Services\FollowUserService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FollowUserController extends Controller
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
     * followIdに紐づくユーザーをフォローする
     *
     * @param integer $followId
     * @return JsonResponse
     */
    public function followUser(int $followId): JsonResponse
    {
        (int) $userId = Auth::id();

        if (!$this->userService->existsUser($followId)) {
            abort(404);
        }
        if ($userId == $followId) {
            abort(403);
        }

        $followUserForm = $this->followUserForm($userId, $followId);
        $this->followUserService->followUser($followUserForm);

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * unfollowIdに紐づくユーザーのフォローを解除
     *
     * @param integer $unfollowId
     * @return JsonResponse
     */
    public function unfollowUser(int $unfollowId): JsonResponse
    {
        (int) $userId = Auth::id();

        if (!$this->userService->existsUser($unfollowId)) {
            abort(404);
        }
        if ($userId == $unfollowId) {
            abort(403);
        }

        $this->followUserService->unfollowUser($userId, $unfollowId);

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * FollowUserの新しいモデルを作成
     *
     * @param integer $userId
     * @param integer $followId
     * @return FollowUser
     */
    private function followUserForm($userId, $followId): FollowUser
    {
        $followUserForm = new FollowUser();
        $followUserForm->following_id = $userId;
        $followUserForm->followed_id = $followId;

        return $followUserForm;
    }
}
