<?php

namespace Tests\Feature\Auth;

use App\Mail\PasswordResetMail;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use Database\Seeders\TmpUserRegistrationTestDataSeeder;
use Database\Seeders\UserTestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{

    use RefreshDatabase;

    /**
     * パスワードリセット用のメール送信機能テスト
     *
     * @return void
     */
    public function test_password_email()
    {
        $user = User::factory()->create();
        $userEmail = $user->email;

        Mail::fake();
        $response = $this->withHeaders([ 'XSRF-TOKEN' => csrf_token(),])
        ->postJson('/api/password/email', ['email' => $userEmail]);

        $this->assertDatabaseHas(TmpUserRegistration::class, [
            'user_id' => $user->id,
            'user_name' => $user->user_name,
            'email' => $user->email,
            'birthday' => $user->birthday,
        ]);

        Mail::assertSent(PasswordResetMail::class, function ($mail) use ($userEmail) {
            return $mail->hasTo($userEmail);
        });

        $response->assertStatus(200)->assertJson(
            [
                'result' => 'success',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * パスワードリセット画面表示判定機能のテスト
     *
     * @return void
     */
    public function test_password_reset()
    {
        $this->seed([
            TmpUserRegistrationTestDataSeeder::class
        ]);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/password/reset/successusertests');

        $response->assertStatus(200)->assertJson(
            [
                'result' => 'success',
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * パスワード変更完了処理のテスト
     *
     * @return void
     */
    public function test_change_password_complete()
    {
        $this->seed([
            TmpUserRegistrationTestDataSeeder::class,
            UserTestDataSeeder::class
        ]);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/password/complete', [
            'token' => 'successusertests',
            'password' => 'testNewUser',
            'password_confirmation' => 'testNewUser'
        ]);

        self::assertDatabaseHas(User::class, [
            'email' => 'newTestTmpUser@gmail.com',
            'birthday' => '1999-12-1',
        ]);

        $this->assertDatabaseMissing(TmpUserRegistration::class, [
            'email' => 'newTestTmpUser@gmail.com',
            'password' => Hash::make('password'),
            'birthday' => '1999-12-1',
        ]);

        $response->assertStatus(200)->assertJson(
            [
                'result' => 'success',
            ],
            JSON_UNESCAPED_UNICODE
        );

        $this->assertTrue(Auth::attempt(['email' => 'newTestTmpUser@gmail.com', 'password' => 'testNewUser']));
    }
}
