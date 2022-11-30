<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
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

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->get('/web/user/regist/complete/testtesttesttest');

        self::assertDatabaseHas(User::class, [
            'user_name' => 'testNewUser',
            'email' => 'newtestUser@test.com',
            'birthday' => '1999-12-1',
        ]);

        $this->assertDatabaseMissing(TmpUserRegistration::class, [
            'user_name' => 'testNewUser',
            'email' => 'newtestUser@test.com',
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

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->get('/web/user/regist/complete/failfailfailfail');

        $this->assertDatabaseMissing(TmpUserRegistration::class, [
            'user_name' => 'testExpieredUser',
            'email' => 'testExpieredUser@test.com',
            'birthday' => '1999-12-1',
        ]);

        $response->assertViewMissing('fail_create_user');
    }
}
