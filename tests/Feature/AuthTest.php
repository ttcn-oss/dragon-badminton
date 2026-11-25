<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_role_user(): void
    {
        $this->post('/register', [
            'name' => 'Nguyen Van A',
            'email' => 'a@test.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', ['email' => 'a@test.com', 'role' => 'user']);
        $this->assertAuthenticated();
    }

    public function test_register_fails_when_password_not_confirmed(): void
    {
        $this->post('/register', [
            'name' => 'B',
            'email' => 'b@test.com',
            'password' => 'secret123',
            'password_confirmation' => 'khac',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'b@test.com']);
    }

    public function test_admin_login_goes_to_admin_dashboard(): void
    {
        User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => 'secret123', 'role' => 'admin']);

        $this->post('/login', ['email' => 'admin@test.com', 'password' => 'secret123'])
            ->assertRedirect('/admin');
    }

    public function test_wrong_password_is_rejected(): void
    {
        User::create(['name' => 'U', 'email' => 'u@test.com', 'password' => 'secret123', 'role' => 'user']);

        $this->post('/login', ['email' => 'u@test.com', 'password' => 'sai'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
