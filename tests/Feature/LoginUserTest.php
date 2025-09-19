<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User; // o tu aggregate User en DDD si ya tienes un Factory

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_user_can_login_and_receive_jwt()
    {
        // Creamos el usuario primero
        $this->postJson('/api/users/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret123',
            'role' => 'client',
        ]);

        // Intentamos hacer login
        $response = $this->postJson('/api/users/login', [
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                 ]);
    }
}
