<?php

namespace Tests\Unit\Requests\Auth;

use App\Http\Requests\API\Auth\RegisterRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Str;
use Tests\CreatesApplication;

class RegisterRequestTest extends TestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    /**
     * バリデーションテスト
     *
     * @param 項目名
     * @param 値
     * @param 期待値
     *
     * //dataProviderと書くことで呼び出せる
     * @dataProvider dataprovider
     */
    public function test_register_request(array $items, array $values, bool $expect)
    {
        $request = new RegisterRequest();
        $rules = $request->rules();
        $dataList = array_combine($items, $values);

        $validator = Validator::make($dataList, $rules);
        $result = $validator->passes();
        $this->assertEquals($expect, $result);
    }

    /**
     * データプロバイダ
     * validationエラー確認用
     *
     * @return dataProvider
     *
     */
    public function dataprovider(): array
    {
        //20年前の今日を取得
        $twentyYearsAgoToday = Carbon::now();
        $twentyYearsAgoToday->subYear(20);
        //20年前の明日を取得
        $twentyYearsAgoTomorrow = Carbon::now();
        $twentyYearsAgoTomorrow->subYear(20);
        $twentyYearsAgoTomorrow->addDay(1);

        $maxLengthEmail = 'abcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcdefghijabcd@gmail.com';

        $maxLengthPassword = Str::random(255);
        $overLimitPassword = Str::random(256);

        return [
            'OK' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', '2000-1-1', 'testPassword', 'testPassword'],
                true
            ],
            '最小値OK' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['a', 't@te.com', $twentyYearsAgoToday, 'password', 'password'],
                true
            ],
            '最大値OK' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                [Str::random(100), $maxLengthEmail, $twentyYearsAgoToday, $maxLengthPassword, $maxLengthPassword],
                true
            ],
            'user_name未入力エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                [null, 'exaple@exaple.com', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'user_name最小文字数未満エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['', 'exaple@exaple.com', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'user_name最大文字数以上エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                [Str::random(256), 'exaple@exaple.com', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'user_name空白入力エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['          ', 'exaple@exaple.comm', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'user_name不正記号エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['<<<<<<<<feaoj', 'exaple@exaple.com', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'email未入力エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', null, '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'email最小文字未満エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 't@', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'email最大文字数以上エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', Str::random(128) . '@' . Str::random(127), '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'email登録済みエラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'test@test.com', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'email形式エラーfront' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', '@testtest.com', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'email形式エラー@なし' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'testtesttest.com', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'email形式エラーback' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'testtesttest@', '2000-1-1', 'testPassword', 'testPassword'],
                false
            ],
            'birthday未入力エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', null, 'testPassword', 'testPassword'],
                false
            ],
            'birthday20歳未満エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', $twentyYearsAgoTomorrow, 'testPassword', 'testPassword'],
                false
            ],
            'password未入力エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', '2000-1-1', null, 'testPassword'],
                false
            ],
            'password最小文字未満エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', '2000-1-1', 'passwor', 'passwor'],
                false
            ],
            'password最大文字数以上エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', '2000-1-1', $overLimitPassword, $overLimitPassword],
                false
            ],
            'password確認用不一致エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', '2000-1-1', 'password', 'testPassword'],
                false
            ],
            'password空白のみ入力エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', '2000-1-1', '         ', '         '],
                false
            ],
            'password英数字のみ入力エラー' => [
                ['user_name', 'email', 'birthday', 'password', 'password_confirmation'],
                ['テスト太郎', 'exaple@exaple.com', '2000-1-1', '<testtest', '<testtest'],
                false
            ],

        ];
    }
}
