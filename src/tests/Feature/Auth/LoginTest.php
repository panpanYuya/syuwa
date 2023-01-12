<?php

namespace Tests\Feature\Auth;

use App\Consts\HttpStatusConst;
use App\Models\Users\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{

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
    public function test_authenticate()
    {
        $email = 'syuwaUser01@syuwa.com';
        $password = 'password';

        $response = $this->postJson('/api/login', ['email' => $email, 'password' =>  $password]);

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
    public function test_authenticate_unauthorized()
    {
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


    /**
     * ログアウト機能をテスト
     *
     * @return void
     */
    public function test_logout()
    {
        $user = $this->createTestUserForm();
        $response = $this->actingAs($user)->postJson('/api/logout');

        $response->assertJson(
            [
                'result' => true,
            ],
        );

        $this->assertGuest('api');

    }

    /**
     * Userの新しいモデルを作成
     *
     * @return User
     */
    private function createTestUserForm(): User
    {
        $user = new User();
        $user->id = 1;
        $user->email = 'syuwaUser01@syuwa.com';
        $user->password = 'password';

        return $user;
    }
}
