<?php

namespace Tests\Unit\Services;

use App\Consts\UtilConst;
use App\Mail\RegistTmpUserMail;
use App\Repositories\RegistTmpUserRepository;
use App\Repositories\RegistUserRepository;
use App\Services\RegistUserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Date;
use Mockery;

class ResistUserServiceTest extends TestCase
{

    private $regist_user_repository_mock;
    private $regist_tmp_user_repository_mock;

    private $tmpRepository;
    private $userRepository;

    use InteractsWithExceptionHandling;



    public function setUp(): void
    {
        parent::setUp();

        $this->tmpRepository = new RegistTmpUserRepository();
        $this->userRepository = new RegistTmpUserRepository();

        $this->regist_user_repository_mock = Mockery::mock(RegistUserRepository::class);
        $this->regist_tmp_user_repository_mock = Mockery::mock(RegistTmpUserRepository::class);
    }


    /**
     * 24時間未満の場合にtrueを返すことをテスト
     *
     * @return void
     */
    public function  test_check_expiration_date()
    {
        $successDate = Date::now();
        $successDate->subHour(23);
        $successDate->subMinute(59);
        $service = new RegistUserService($this->regist_tmp_user_repository_mock, $this->regist_user_repository_mock);
        (bool) $result = $service->checkExpirationDate($successDate);
        $this->assertTrue($result);
    }

    /**
     * 24時間経過していた際にfalseを返すことをテスト
     *
     * @return void
     */
    public function  test_check_expiration_date_error()
    {
        $successDate = Date::now();
        $successDate->subHour(24);
        $service = new RegistUserService($this->regist_tmp_user_repository_mock, $this->regist_user_repository_mock);
        (bool) $result = $service->checkExpirationDate($successDate);
        $this->assertFalse($result);
    }


    /**
     * 作成したトークンが16桁であることを確認
     *
     * @return void
     */
    public function test_create_tmp_token()
    {
        $service = new RegistUserService($this->userRepository, $this->regist_user_repository_mock);
        $testToken = $service->createTmpToken();
        $this->assertEquals(UtilConst::TOKENCOUNT, mb_strlen($testToken));
    }

    /**
     * 仮登録のメールを送信しているかテスト
     *
     * @return void
     */
    public function test_send_tmp_mail()
    {
        $testMailAddress = 'syuwaUser01@syuwa.com';
        $userName = 'テストネーム';

        Mail::fake();
        $token = Str::random(UtilConst::TOKENCOUNT);
        $service = new RegistUserService($this->regist_tmp_user_repository_mock, $this->regist_user_repository_mock);
        $service->sendTemporaryMail($testMailAddress, $userName, $token);

        // メッセージが指定したユーザーに届いたことをアサート
        Mail::assertSent(RegistTmpUserMail::class, function ($mail) use ($testMailAddress) {
            return $mail->hasTo($testMailAddress);
        });

        // メールが1回送信されたことをアサート
        Mail::assertSent(RegistTmpUserMail::class, 1);
    }

    /**
     * パスワードをハッシュ化関数をテスト
     *
     * @return void
     */
    public function test_hashed_password()
    {
        $testPassword = 'testtest';
        $service = new RegistUserService($this->regist_tmp_user_repository_mock, $this->regist_user_repository_mock);
        $hashedPassword = $service->hashedPassword($testPassword);
        $this->assertTrue(Hash::check('testtest', $hashedPassword));
    }
}
