<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\FollowUser;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    private UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function followUser(int $followId): JsonResponse
    {
        (int) $userId = Auth::id();

        if(!$this->userService->existsUser($followId)) {
            abort(404);
        }
        if ($userId == $followId) {
            abort(403);
        }

        $followUserForm = $this->followUserForm($userId, $followId);
        $this->userService->followUser($followUserForm);

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
    // private function followUserForm(int $userId, int $followId): FollowUser
    private function followUserForm($userId, $followId): FollowUser
    {
        $followUserForm = new FollowUser();
        $followUserForm->user_id = $userId;
        $followUserForm->follow_id = $followId;

        return $followUserForm;
    }
}
