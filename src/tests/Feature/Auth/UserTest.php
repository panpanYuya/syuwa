<?php

namespace Tests\Feature\Auth;

use App\Models\FollowUser;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function test_follow_user()
    {
        $user = User::factory()->create();

        $email = 'test@test.com';
        $password = 'password';

        $response = $this->postJson('/api/login', ['email' => $email, 'password' =>  $password]);

        // Sanctum::actingAs(
        //     User::factory()->count(2)->create(),
        //     ['view-tasks']
        // );

        $response = $this->withHeaders([
            'XSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/user/follow/' . $user->id);


        $this->assertDatabaseHas(FollowUser::class, [
            'user_id' => Auth::id(),
            'follow_id' => $user->id,
        ]);

        $response->assertJson(
            [
                'result' => true,
            ],
            JSON_UNESCAPED_UNICODE
        );
    }
}
