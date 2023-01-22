<?php

namespace Tests\Feature\Auth;

use App\Models\FollowUser;
use App\Models\Users\User;
use Database\Seeders\FollowUserTestDataSeeder;
use Database\Seeders\UserTestDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FollowUserTest extends TestCase
{

    use RefreshDatabase;

    public function test_follow_user()
    {
        $user = User::factory()->create();

        $email = 'syuwaUser01@syuwa.com';
        $password = 'password';

        $response = $this->postJson('/api/login', ['email' => $email, 'password' =>  $password]);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->putJson('/api/user/follow/' . $user->id);


        $this->assertDatabaseHas(FollowUser::class, [
            'following_id' => Auth::id(),
            'followed_id' => $user->id,
        ]);

        $response->assertJson(
            [
                'result' => true,
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function test_unfollow_user()
    {
        $this->seed([
            UserTestDataSeeder::class,
            FollowUserTestDataSeeder::class
        ]);

        $email = 'syuwaUser01@syuwa.com';
        $password = 'password';

        $response = $this->postJson('/api/login', ['email' => $email, 'password' =>  $password]);

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->deleteJson('/api/user/unfollow/9999999');


        $this->assertDatabaseMissing(FollowUser::class, [
            'following_id' => Auth::id(),
            'followed_id' => 9999999,
        ]);

        $response->assertJson(
            [
                'result' => true,
            ],
            JSON_UNESCAPED_UNICODE
        );
    }
}
