<?php

namespace Tests\Feature\Auth;

use App\Consts\HttpStatusConst;
use App\Models\Users\User;
use Database\Seeders\UserSeeder;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase{

    use RefreshDatabase;

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder = UserSeeder::class;

    /**
     * ログインの正常系のテスト
     *
     * @return void
     */
    public function test_authenticate(){
        $email = 'test@test.com';
        $password = 'password';

        $response = $this->postJson('/api/login', ['email' => $email,'password' =>  $password]);

        $this->assertAuthenticated();
        $response->assertJson(
            [
                'status' => HttpStatusConst::SUCCESS,
                'message' => 'ログインに成功しました。',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * ログインの401エラーテスト
     *
     * @return void
     */
    public function test_authenticate_unauthorized(){
        $email = 'password@test.com';
        $password = 'testtest';

        $response = $this->postJson('/api/login', ['email' => $email, 'password' => $password]);

        $response->assertJson(
            [
                'status' => HttpStatusConst::AUTH_ERROR,
                'message' => 'ログイン情報が正しくありません。',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }
}
