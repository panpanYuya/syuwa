<?php

namespace App\Http\Controllers;

use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use App\Services\RegistUserService;

class UserController extends Controller
{
    private RegistUserService $registUserService;

    public function __construct(
        RegistUserService $registUserService
    ) {
        $this->registUserService = $registUserService;
    }

    /**
     * URLから仮登録ユーザーを本登録する処理
     *
     * @return view
     */
    public function registUserComplete()
    {
        $tmpUser = $this->registUserService->findTmpUserByToken(request('token'));
        if ($this->registUserService->checkExpirationDate($tmpUser->updated_at)) {
            $user = $this->createUserForm($tmpUser);
            $this->registUserService->createNewUser($user);
            $this->registUserService->deleteRegistedTmpUser($tmpUser);
            return view('create_user_complete');
        }

        $this->registUserService->deleteRegistedTmpUser($tmpUser);
        return view('fail_create_user');
    }

    /**
     * 仮登録用ユーザーのモデルをユーザーモデルに変換
     *
     * @param TmpUserRegistration $tmpUser
     * @return User
     */
    public function createUserForm(TmpUserRegistration $tmpUser): User
    {
        $user = new User();
        $user->user_name = $tmpUser->user_name;
        $user->email = $tmpUser->email;
        $user->password = $tmpUser->password;
        $user->birthday = $tmpUser->birthday;

        return $user;
    }
}
