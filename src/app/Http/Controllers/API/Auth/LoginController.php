<?php

namespace App\Http\Controllers\API\Auth;

use App\Consts\HttpStatusConst;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\API\Auth\LoginRequest;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    private  AuthManager $auth;

    /**
     * @param AuthManager $auth
     */
    public function __construct(
        AuthManager $auth
    ) {
        $this->auth = $auth;
    }


    public function authenticate(LoginRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $request->session()->regenerate();
                $status = HttpStatusConst::SUCCESS;
                $message = __('auth.success');
            } else {
                $status = HttpStatusConst::AUTH_ERROR;
                $message = __('auth.unauthorized');
            }
        } catch (Exception $error) {
            $status = HttpStatusConst::SERVER_ERROR;
            $message = __('error.database.auth');
        } finally {
            //TODOエラーの共通処理実装後に修正する
            return response()->json([
                'status' => $status,
                'message' => $message,
            ]);
        }
    }


    /**
     * ログアウト
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        if ($this->auth->guard()->guest()) {
            return new JsonResponse([
                'message' => 'Already Unauthenticated.',
            ]);
        }

        Auth::guard('api')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'result' => true,
        ]);
    }
}
