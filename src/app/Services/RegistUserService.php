<?php

namespace App\Services;

use App\Consts\UrlConst;
use App\Consts\UtilConst;
use App\Models\Users\TmpUserRegistration;
use App\Repositories\RegistTmpUserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Date;
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
     * ユーザー認証テーブルに登録する処理を行う関数
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
            throw new Exception($e);
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
    public function createRegistUser(string $token): TmpUserRegistration
    {
        return $this->registTmpUserRepository->findTmpUser($token);
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
}