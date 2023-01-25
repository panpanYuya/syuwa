<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use Database\Seeders\TmpUserRegistrationTestDataSeeder;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * すでに仮登録済みのユーザーを新規登録するテスト
     *
     * @return void
     */
    public function test_create_new_user()
    {
        $this->seed([
            TmpUserRegistrationTestDataSeeder::class
        ]);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->get('/web/user/regist/complete/passwordsuccessu');

        self::assertDatabaseHas(User::class, [
            'email' => 'newtestUser@gmail.com',
            'birthday' => '1999-12-1',
        ]);

        $this->assertDatabaseMissing(TmpUserRegistration::class, [
            'email' => 'newtestUser@gmail.com',
            'birthday' => '1999-12-1',
        ]);

        $response->assertViewMissing('create_user_complete');
    }

    /**
     * 期限切れの仮登録ユーザーを削除、期限切れのViewを表示するテスト
     *
     * @return void
     */
    public function test_create_new_user_fail()
    {
        $this->seed([
            TmpUserRegistrationTestDataSeeder::class
        ]);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->get('/web/user/regist/complete/passwordfaildata');

        $this->assertDatabaseMissing(TmpUserRegistration::class, [
            'email' => 'testExpieredUser@gmail.com',
            'birthday' => '1999-12-1',
        ]);

        $response->assertViewMissing('fail_create_user');
    }
}
