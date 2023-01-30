<?php

namespace Tests\Feature\Auth;

use App\Mail\CertifyNewMailAddress;
use App\Models\Users\User;
use Database\Seeders\TmpUserRegistrationTestDataSeeder;
use Database\Seeders\UserTestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserInfoEditTest extends TestCase
{

    use RefreshDatabase;

    /**
     * ユーザー情報ページ表示機能のテスト
     *
     * @return void
     */
    public function test_user_info_edit()
    {

        $user = User::factory()->create();

        $this->postJson('/api/login', ['email' => $user->email, 'password' =>  'password']);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->getJson('/api/user/edit/' . $user->id);


        $response->assertStatus(200)->assertJson(
            [
                'result' => true,
                'User' => [
                    'id' => $user->id,
                    'user_name' => $user->user_name,
                    'email' => $user->email,
                ],
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * メールアドレスを含まないユーザー情報の変更処理のテスト
     *
     * @return void
     */
    public function test_update_user_info()
    {
        $this->seed([
            UserTestDataSeeder::class
        ]);

        $id = 1000000;
        $email = 'newtestnewUser@gmail.com';
        $password = 'password';

        $response = $this->postJson('/api/login', ['email' => $email, 'password' =>  $password]);

        $this->postJson('/api/login', ['email' => $email, 'password' =>  'password']);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/user/update', [
                'user_id' => $id,
                'user_name' => 'syuwaUser01',
                'email' => $email,
                'password' => 'syuwaUser01',
                'password_confirmation' => 'syuwaUser01'
        ]);

        $response->assertStatus(200)->assertJson(
            [
                'result' => true,
                'temporary' => false,
                'message' => '個人情報の変更が完了しました。'
            ],
            JSON_UNESCAPED_UNICODE
        );

        $this->assertDatabaseHas('users', [
            'id' => $id,
            'user_name' => 'syuwaUser01',
            'email' => $email,
        ]);

        $this->postJson('/api/logout');
        $this->postJson('/api/login', ['email' => $email, 'password' =>  'syuwaUser01']);
    }


    /**
     * メールアドレスを含むユーザー情報の変更受付処理のテスト
     *
     * @return void
     */
    public function test_update_user_email()
    {
        $this->seed([
            UserTestDataSeeder::class,
            TmpUserRegistrationTestDataSeeder::class
        ]);

        $id = 1000000;
        $email = 'newtestnewUser@gmail.com';
        $password = 'password';

        //変更後の情報
        $changedUserName = 'syuwaUser01';
        $changedEmail = 'testUser99991231@gmail.com';
        $changedPassword = 'syuwaUser01';

        $response = $this->postJson('/api/login', ['email' => $email, 'password' =>  $password]);

        $this->postJson('/api/login', ['email' => $email, 'password' =>  'password']);

        Mail::fake();
        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
            ])->postJson('/api/user/update', [
                'user_id' => $id,
                'user_name' => $changedUserName,
                'email' => $changedEmail,
                'password' => $changedPassword,
                'password_confirmation' => $changedPassword
            ]);

            $response->assertStatus(200)->assertJson(
            [
                'result' => true,
                'temporary' => true,
                'message' => 'メールを送信しました。メールから個人情報変更を完了させてください。'
            ],
            JSON_UNESCAPED_UNICODE
        );

        $this->assertDatabaseHas('tmp_user_registrations', [
            'user_id' => $id,
            'user_name' => $changedUserName,
            'email' => $changedEmail,
        ]);


        $this->assertDatabaseMissing('users', [
            'id' => $id,
            'user_name' => $changedUserName,
            'email' => $changedEmail,
        ]);

        Mail::assertSent(CertifyNewMailAddress::class, function ($mail) use ($changedEmail) {
            return $mail->hasTo($changedEmail);
        });

    }

    /**
     * メールアドレスを含むユーザー情報の変更処理のテスト
     *
     * @return void
     */
    public function test_completed_email_certification()
    {
        $id = 1000000;
        $userName = '新しいテストユーザー';
        $email = 'newTestTmpUser@gmail.com';

        $this->seed([
            UserTestDataSeeder::class,
            TmpUserRegistrationTestDataSeeder::class
        ]);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/user/update/complete/successusertests');

        $this->assertDatabaseHas('users', [
            'id' => $id,
            'user_name' => $userName,
            'email' => $email,
        ]);


        $this->assertDatabaseMissing('tmp_user_registrations', [
            'user_id' => $id,
            'user_name' => $userName,
            'email' => $email,
        ]);


        $response->assertStatus(200)->assertJson(
            [
                'result' => true,
                'message' => 'ユーザー情報の変更を完了しました。'
            ],
            JSON_UNESCAPED_UNICODE
        );

    }
}
