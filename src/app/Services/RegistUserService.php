<?php

namespace App\Services;

use App\Consts\UrlConst;
use App\Consts\UtilConst;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use App\Repositories\RegistTmpUserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Date;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class RegistUserService
{
    private RegistTmpUserRepository $registTmpUserRepository;

    public function __construct(
        RegistTmpUserRepository $registTmpUserRepository
    ) {
        $this->registTmpUserRepository = $registTmpUserRepository;
    }

    /**
     * 仮登録テーブルに登録する処理
     *
     * @param User $user
     * @return void
     */
    public function createTmpUser(TmpUserRegistration $tmpUser)
    {
        $tmpUser->password = $this->hashedPassword($tmpUser->password);

        try {
            $this->registTmpUserRepository->createNewTmpUser($tmpUser);
        } catch (Throwable $e) {
            throw new Exception($e);
        }
    }

    /**
     * 仮登録ユーザーを本登録する処理
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function createNewUser(TmpUserRegistration $tmpUser)
    {
        $user = $this->createUserForm($tmpUser);
        try
        {
            DB::beginTransaction();
            $this->registTmpUserRepository->createNewUser($user);
            DB::commit();
        } catch(Throwable $e)
        {
            abort(500);
        }

    }

    /**
     * 仮登録情報を更新する処理
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function updateTmpUser(TmpUserRegistration $tmpUser)
    {
        $tmpUser->password = $this->hashedPassword($tmpUser->password);

        try {
            $this->registTmpUserRepository->updateNewTmpUser($tmpUser);
        } catch (Throwable $e) {
            abort(500);
        }
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
     * トークンに紐づく仮登録情報を取得
     *
     * @param string $token
     * @return TmpUserRegistration
     */
    public function findTmpUserByToken(string $token): TmpUserRegistration
    {
        return $this->registTmpUserRepository->findTmpUserByToken($token);
    }

    /**
     * 仮登録用のURLが有効期限切れではないかを確認
     * trueの場合は有効期限内
     * falseの場合は有効期限外
     *
     * @param Date $endTime
     * @return boolean
     */
    public function checkExpirationDate(DateTime $expirationDate): bool
    {
        $expirationDate->addHour(UtilConst::ONE_DAY_TO_HOUR);
        return Carbon::now() < $expirationDate;
    }

    /**
     * 引数の文字列をhash化
     *
     * @param string $password
     * @return string
     */
    public function hashedPassword(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * 仮登録用のトークンを作成
     *
     * @return string
     */
    public function createTmpToken(): string
    {
        return Str::random(UtilConst::TOKENCOUNT);
    }

    /**
     * メールに記載する登録メールに記載するURLを作成
     *
     * @param string $token
     * @return string
     */
    public function createRegistUrl(string $token): string
    {
        return config('app.url') . UrlConst::CREATEANDUPDATEURL . $token;
    }


    /**
     * ユーザーテーブルに登録する新規ユーザーの情報をセット
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
