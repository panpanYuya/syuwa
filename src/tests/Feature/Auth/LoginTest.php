<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_authenticate()
    {
        $response = $this->postJson('/api/login', ['email' => 'test@test.com', 'password' => 'password']);

        $response->assertStatus(200);
    }
}
