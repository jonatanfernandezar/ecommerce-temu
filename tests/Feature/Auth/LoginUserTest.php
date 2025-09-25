<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class LoginUserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::statement('TRUNCATE TABLE users');
    }

    /** @test */
    public function a_user_can_login_and_receive_jwt()
    {
        $this->postJson('/api/users/register', [
            'name'     => 'Jane Doe',
            'email'    => 'jane@example.com',
            'password' => 'secret123',
            'role'     => 'client',
        ])->assertStatus(201);

        $response = $this->postJson('/api/users/login', [
            'email'    => 'jane@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token','token_type','expires_in']);
    }

    /** @test */
    public function cannot_login_with_invalid_credentials()
    {
        $this->postJson('/api/users/register', [
            'name'     => 'Tester',
            'email'    => 'tester@example.com',
            'password' => 'valid-password',
            'role'     => 'client',
        ])->assertStatus(201);

        $response = $this->postJson('/api/users/login', [
            'email'    => 'tester@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }
}
