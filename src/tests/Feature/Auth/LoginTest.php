<?php

namespace Tests\Feature\Auth;

use App\Consts\HttpStatusConst;
use App\Models\Users\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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
        $response->assertStatus(200)->assertJson(
            [
                'userId' => Auth::id()
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

        $response->assertStatus(401)->assertJson(
            [
                'message' => '認証情報が正しくないため、画面を表示できません。',
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
     * ログイン状態確認機能のテスト
     *
     * @return void
     */
    public function test_check_login()
    {
        $user = $this->createTestUserForm();
        $response = $this->actingAs($user)->postJson('/api/user/check');

        $response->assertJson(
            [
                'result' => true,
            ],
        );
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
