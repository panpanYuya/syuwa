<?php

namespace App\Services;

use App\Models\Users\User;
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
    public function registTmpUser(User $user)
    {
        //TODO ハッシュ化メソッドのコメントを追加
        //TODO ハッシュ化メソッドを呼び出す
        $user->password = $this.hashedPassword($user->password);
        //TODO 仮登録テーブルのinterfaceに仮登録テーブルに登録する処理を追加
        //TODO 仮登録テーブルRepositoryに値を登録する処理を各
        //TODO ↑を呼び出す処理を記述する

    }

    public function checkTmpUser(string $email): bool
    {
        return $this->registTmpUserRepository->checkTmpUser($email);
    }

    public function hashedPassword(string $password):string{
        return Hash::make($password);
    }
}
