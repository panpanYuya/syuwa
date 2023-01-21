<?php

namespace App\Http\Controllers\API\Auth;

use App\Consts\ErrorMessageConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\PasswordCompleteRequest;
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
     * パスワードリセット用のメール送信
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
    public function passwordReset(string $token):JsonResponse
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

    /**
     * パスワード再登録
     *
     * @param PasswordCompleteRequest $request
     * @return JsonResponse
     */
    public function changePasswordComplete(PasswordCompleteRequest $request): JsonResponse
    {
        $tmpUser = $this->registUserService->findTmpUserByToken($request->token);
        $user = $this->createUserForm($tmpUser, $request->password);
        $this->userService->updateUser($user);
        $this->registUserService->deleteRegistedTmpUser($tmpUser);
        return response()->json([
            'result' => 'success'
        ], 200);

    }


    /**
     * ユーザーのモデルを仮登録用ユーザーモデルに変換
     *
     * @param User $user
     * @param string $token
     * @return TmpUserRegistration
     */
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

    /**
     * 仮登録用ユーザーのモデルをユーザーモデルに変換
     *
     * @param TmpUserRegistration $tmpUser
     * @param string $password
     * @return User
     */
    private function createUserForm(TmpUserRegistration $tmpUser, string $password): User
    {
        $user = new User();
        $user->id = $tmpUser->user_id;
        $user->user_name = $tmpUser->user_name;
        $user->email = $tmpUser->email;
        $user->password = $this->registUserService->hashedPassword($password);
        $user->birthday = $tmpUser->birthday;

        return $user;
    }
}
