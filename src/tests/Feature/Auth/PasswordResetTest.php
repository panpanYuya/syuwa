<?php

namespace Tests\Feature\Auth;

use App\Mail\PasswordResetMail;
use App\Models\Users\TmpUserRegistration;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
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
        $this->withHeaders([ 'XSRF-TOKEN' => csrf_token(),])
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
    }
}
