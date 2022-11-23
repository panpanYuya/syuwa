<?php

namespace App\Http\Controllers;

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
            $this->registUserService->createNewUser($tmpUser);
            $this->registUserService->deleteRegistedTmpUser($tmpUser);
            return view('create_user_complete');
        }

        $this->registUserService->deleteRegistedTmpUser($tmpUser);
        return view('fail_create_user');
    }
}
