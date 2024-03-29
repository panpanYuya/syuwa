<?php

namespace App\Services;

use App\Consts\UrlConst;
use App\Consts\UtilConst;
use App\Mail\CertifyNewMailAddress;
use App\Mail\RegistTmpUserMail;
use App\Mail\PasswordResetMail;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use App\Repositories\RegistTmpUserRepository;
use App\Repositories\RegistUserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class RegistUserService
{
    private RegistTmpUserRepository $registTmpUserRepository;
    private RegistUserRepository $registUserRepository;

    public function __construct(
        RegistTmpUserRepository $registTmpUserRepository,
        RegistUserRepository $registUserRepository
    ) {
        $this->registTmpUserRepository = $registTmpUserRepository;
        $this->registUserRepository = $registUserRepository;
    }

    /**
     * 仮登録テーブルに登録する処理
     *
     * @param User $user
     * @return void
     */
    public function createTmpUser(TmpUserRegistration $tmpUser)
    {
        try {
            $this->registTmpUserRepository->createNewTmpUser($tmpUser);
        } catch (Throwable $e) {
            abort(500);
        }
    }

    /**
     * 仮登録ユーザーを本登録する処理
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function createNewUser(User $user)
    {
        try
        {
            $this->registUserRepository->createNewUser($user);
        } catch(Throwable $e)
        {
            abort(500);
        }

    }

    /**
     * 仮登録情報を更新
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function updateTmpUser(TmpUserRegistration $tmpUser)
    {
        try {
            $this->registTmpUserRepository->updateNewTmpUser($tmpUser);
        } catch (Throwable $e) {
            abort(500);
        }
    }

    /**
     * 仮登録情報を削除
     *
     * @param TmpUserRegistration $tmpUser
     * @return void
     */
    public function deleteRegistedTmpUser(TmpUserRegistration $tmpUser)
    {
        try {
            $this->registTmpUserRepository->deleteTmpUser($tmpUser);
        } catch (Throwable $e) {
            abort(500);
        }
    }

    /**
     * 仮登録テーブルに登録データが存在するか確認
     *
     * @param string $email
     * @return boolean
     */
    public function checkTmpUser(string $email): bool
    {
        return $this->registTmpUserRepository->checkTmpUser($email);
    }

    /**
     * メールアドレスに紐づく仮登録ユーザー情報を取得する
     *
     * @param string $email
     * @return Collection
     */
    public function findTmpUserByEmail(string $email): Collection
    {
        return $this->registTmpUserRepository->findTmpUserByEmail($email);
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
     * 仮登録用のURLが有効期限切れではないか確認
     * 有効期限は24時間
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
     * 登録するトークンがすでに仮登録テーブルに登録されていないかを確認
     *
     * @return string
     */
    public function createTmpToken(): string
    {
        $tokenFlg = false;
        while (!$tokenFlg)
        {
            $token = Str::random(UtilConst::TOKENCOUNT);
            if(!$this->registTmpUserRepository->checkToken($token))
            {
                $tokenFlg =true;
            }
        }
        return $token;
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
     * パスワードリセットメールに記載するURLを作成
     *
     * @param string $token
     * @return string
     */
    public function createPasswordResetUrl(string $token): string
    {
        return env('FRONT_URL') . UrlConst::CREATEPASSWORDRESETURL . $token;
    }

    /**
     * メールアドレス変更完了メールに記載するURLを作成
     *
     * @param string $token
     * @return string
     */
    public function createCertifyNewEmail(string $token):string
    {
        return env('FRONT_URL') . UrlConst::CERTIFYNEWEMAIL . $token;
    }

    /**
     * 本登録メールを送信
     *
     * @param string $email
     * @param string $userName
     * @param string $token
     * @return void
     */
    public function sendTemporaryMail(string $email, string $userName, string $token)
    {
        $registUrl = $this->createRegistUrl($token);
        try {
            Mail::to($email)->send(new RegistTmpUserMail($userName, $registUrl));
        } catch (Throwable $e) {
            abort(500);
        }
    }

    /**
     * パスワードリセット用のメールを送信
     *
     * @param string $email
     * @param string $token
     * @return void
     */
    public function sendPasswordResetMail(string $email, string $token)
    {
        $registUrl = $this->createPasswordResetUrl($token);
        try {
            Mail::to($email)->send(new PasswordResetMail($registUrl));
        } catch (Throwable $e) {
            abort(500);
        }
    }

    /**
     * メールアドレス変更認証用のメールを送信
     *
     * @param string $email
     * @param string $token
     * @return void
     */
    public function sendCertifyNewAddressMail(string $email, string $token)
    {
        $certifyUrl = $this->createCertifyNewEmail($token);
        try {
            Mail::to($email)->send(new CertifyNewMailAddress($certifyUrl));
        } catch (Throwable $e) {
            abort(500);
        }
    }


}
