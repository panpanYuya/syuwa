<?php

namespace App\Services;

use App\Models\Users\TmpUserRegistration;
use App\Repositories\RegistTmpUserRepository;
use Illuminate\Support\Facades\Hash;

class RegistUserService
{
    private RegistTmpUserRepository $registTmpUserRepository;

    public function __construct(
        RegistTmpUserRepository $registTmpUserRepository
    ) {
        $this->registTmpUserRepository = $registTmpUserRepository;
    }
    /**
     * ユーザー認証テーブルに登録する処理を行う関数
     *
     * @param User $user
     * @return void
     */
    public function registTmpUser(TmpUserRegistration $tmpUser)
    {
        $tmpUser->password = $this->hashedPassword($tmpUser->password);
        return $tmpUser;
        //TODO 仮登録テーブルのinterfaceに仮登録テーブルに登録する処理を追加
        //TODO 仮登録テーブルRepositoryに値を登録する処理を書く
        //TODO ↑を呼び出す処理を記述する

    }

    /**
     * 仮登録テーブルに登録データが存在確認
     *
     * @param string $email
     * @return boolean
     */
    public function checkTmpUser(string $email): bool
    {
        return $this->registTmpUserRepository->checkTmpUser($email);
    }

    /**
     * 引数の文字列をhash化
     *
     * @param string $password
     * @return string
     */
    public function hashedPassword(string $password):string
    {
        return Hash::make($password);
    }
}
