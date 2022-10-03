<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\API\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{


    public function authenticate(LoginRequest $request): JsonResponse{

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            //TODO正常に動く用になった時にもどす
            // $request->session()->regenerate();
            return  response()->json([
             Auth::user()
            ]);
        }

        return response()->json([
            'result' => false,
        ]);
    }
}
