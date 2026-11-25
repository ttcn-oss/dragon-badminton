<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourtTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role): User
    {
        return User::create([
            'name' => 'Test '.$role,
            'email' => $role.'@test.com',
            'password' => 'secret123',
            'role' => $role,
        ]);
    }

    public function test_admin_can_see_and_create_court(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->get('/admin/courts')->assertOk();

        $this->actingAs($admin)
            ->post('/admin/courts', ['name' => 'San 1', 'status' => 'active', 'price_per_hour' => 80000])
            ->assertRedirect('/admin/courts');

        $this->assertDatabaseHas('courts', ['name' => 'San 1', 'price_per_hour' => 80000]);
    }

    public function test_normal_user_cannot_open_admin_pages(): void
    {
        $user = $this->makeUser('user');

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->get('/admin/courts')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_home_page_lists_active_courts(): void
    {
        Court::create(['name' => 'San Test', 'status' => 'active', 'price_per_hour' => 90000]);

        $this->get('/')->assertOk()->assertSee('San Test');
    }
}
