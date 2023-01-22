<?php

namespace Tests\Feature\Auth;

use App\Models\Users\TmpUserRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use RefreshDatabase;

    /**
     * 新たに仮登録するユーザーのテテスト
     *
     * @return void
     */
    public function test_register_tmpUser()
    {
        //
        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/user/regist', [
            'user_name' => 'Sally',
            'email' => 'test3@test.com',
            'birthday' => '2002-11-1',
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ]);

        self::assertDatabaseHas(TmpUserRegistration::class, [
            'user_name' => 'Sally',
            'email' => 'test3@test.com',
            'birthday' => '2002-11-1',
        ]);
        $response->assertStatus(200)->assertJson(['result' => 'success']);
    }

    /**
     * すでに仮登録済みのユーザーを更新するテスト
     *
     * @return void
     */
    public function test_register_exsists_user()
    {

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/user/regist', [
            'user_name' => 'Bakky',
            'email' => 'test3@test.com',
            'birthday' => '1905-1-5',
            'password' => 'testtest',
            'password_confirmation' => 'testtest',
        ]);

        self::assertDatabaseHas(TmpUserRegistration::class, [
            'user_name' => 'Bakky',
            'email' => 'test3@test.com',
            'birthday' => '1905-1-5',
        ]);

        $response->assertStatus(200)->assertJson(['result' => 'success']);
    }
}
