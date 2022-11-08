<?php

namespace App\Repositories;

use App\Models\Users\TmpUserRegistration;

class RegistTmpUserRepository implements RegistTmpUserInterface
{
    //TODO コメントを追加する
    public function registNewTmpUser(): TmpUserRegistration
    {
        $tmpUser= new TmpUserRegistration();

        return $tmpUser;
    }

    //TODO コメントを追加する
    public function checkTmpUser(string $email):bool
    {
        return TmpUserRegistration::where('email', $email)->exists();
    }
}
