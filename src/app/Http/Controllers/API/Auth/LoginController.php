<?php

namespace App\Http\Controllers\API\Auth;

use App\Consts\HttpStatusConst;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\API\Auth\LoginRequest;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * ログイン
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function authenticate(LoginRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $request->session()->regenerate();
                return response()->json([
                    'userId' => Auth::id()
                ]);
            }
        } catch (Exception $error) {
            abort(500);
        }
        //カスタムメッセージをフロントエンド側に渡す為にabort関数を使用
        abort(401);
    }


    /**
     * ログアウト
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('api')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'result' => true,
        ]);
    }


    /**
     * ユーザーのログイン状態を確認
     *
     * @return JsonResponse
     */
    public function checkLogin():JsonResponse
    {
        if (Auth::check()) {
            return response()->json([
                'result' => true,
            ]);
        }

        abort(401);
    }
}
