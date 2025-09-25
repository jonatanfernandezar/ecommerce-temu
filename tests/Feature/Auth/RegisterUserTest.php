<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class RegisterUserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::statement('TRUNCATE TABLE users');
    }

    /** @test */
    public function a_user_can_register()
    {
        $response = $this->postJson('/api/users/register', [
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => 'secret123',
            'role'     => 'client',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id','name','email','role']);
    }

    /** @test */
    public function cannot_register_with_existing_email()
    {
        $this->postJson('/api/users/register', [
            'name'     => 'Existing',
            'email'    => 'existing@example.com',
            'password' => 'secret123',
            'role'     => 'client',
        ])->assertStatus(201);

        $response = $this->postJson('/api/users/register', [
            'name'     => 'Another',
            'email'    => 'existing@example.com',
            'password' => 'secret123',
            'role'     => 'client',
        ]);

        $response->assertStatus(422);
    }
}
