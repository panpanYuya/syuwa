<?php

namespace Tests\Unit\Requests\Auth;

use App\Http\Requests\API\Auth\LoginRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    use RefreshDatabase;
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
    public function test_login_request(array $items, array $values, bool $expect)
    {
        $request = new LoginRequest();
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
        return [
            'OK' => [
                ['email', 'password'],
                ['syuwaUser01@syuwa.com', 'password'],
                true
            ],
            'email必須エラー1' => [
                ['email', 'password'],
                [null, 'password'],
                false
            ],
            'email必須エラー2' => [
                ['email', 'password'],
                ['', 'password'],
                false
            ],
            'email形式エラー1' => [
                ['email', 'password'],
                ['test@', 'password'],
                false
            ],
            'email形式エラー2' => [
                ['email', 'password'],
                ['@example.com', 'password'],
                false
            ],
            'password必須エラー1' => [
                ['email', 'password'],
                ['test@example.com', ''],
                false
            ],
            'password必須エラー2' => [
                ['email', 'password'],
                ['test@example.com', null],
                false
            ],

        ];
    }
}
