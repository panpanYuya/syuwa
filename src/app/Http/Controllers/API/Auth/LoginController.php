<?php

namespace App\Http\Controllers\API\Auth;

use App\Consts\HttpStatusConst;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\API\Auth\LoginRequest;
use Exception;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    public function authenticate(LoginRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                //TODO正常に動く用になった時にもどす
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
}
