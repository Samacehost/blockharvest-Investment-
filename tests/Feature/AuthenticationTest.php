<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_user_and_wallet(): void
    {
        $response = $this->post('/register', [
            'first_name' => 'Alexander',
            'last_name' => 'Wright',
            'email' => 'alex@example.com',
            'preferred_currency' => 'USD',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'alex@example.com']);
        $this->assertDatabaseHas('wallets', ['currency_code' => 'USD']);
    }

    public function test_login_authenticates_investor(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'investor',
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
