<?php

namespace App\Http\Controllers\API\Auth;

use App\Consts\ErrorMessageConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\PasswordResetRequest;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use App\Services\RegistUserService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    private UserService $userService;
    private RegistUserService $registUserService;

    public function __construct(
        UserService $userService,
        RegistUserService $registUserService
    ) {
        $this->userService = $userService;
        $this->registUserService = $registUserService;
    }

    /**
     * パスワードリセット
     *
     * @param PasswordResetRequest $request
     * @return JsonResponse
     */
    public function checkEmail(PasswordResetRequest $request): JsonResponse
    {
        $user = $this->userService->findUserByEmail($request->email);

        $token = $this->registUserService->createTmpToken();

        $tmpUser = $this->tmpUserForm($user, $token);

        if ($this->registUserService->checkTmpUser($tmpUser->email)) {
            $this->registUserService->updateTmpUser($tmpUser);
        } else {
            $this->registUserService->createTmpUser($tmpUser);
        }

        $this->registUserService->sendPasswordResetMail($tmpUser->email, $tmpUser->token);

        return response()->json([
            'result' => 'success',
        ], 200);
    }

    /**
     * パスワードリセット
     *
     * @param string $token
     * @return JsonResponse
     */
    public function passwordReset(string $token)
    {
        $tmpUser = $this->registUserService->findTmpUserByToken($token);

        if (!$this->registUserService->checkExpirationDate($tmpUser->updated_at)) {
            $this->registUserService->deleteRegistedTmpUser($tmpUser);
            return response()->json([
                'result' => 'error',
                'message' => ErrorMessageConst::EXPIRATION_DATE,
            ], 200);
        }
        return response()->json([
            'result' => 'success'
        ], 200);
    }

    private function tmpUserForm(User $user, string $token): TmpUserRegistration
    {
        $tmpUser = new TmpUserRegistration();
        $tmpUser->user_id = $user->id;
        $tmpUser->user_name = $user->user_name;
        $tmpUser->email = $user->email;
        $tmpUser->password = $user->password;
        $tmpUser->birthday = $user->birthday;
        $tmpUser->token = $token;

        return $tmpUser;



    }
}
