<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $email, string $role = 'user'): User
    {
        return User::create([
            'name' => 'Test '.$email,
            'email' => $email,
            'password' => 'secret123',
            'role' => $role,
        ]);
    }

    private function makeCourt(): Court
    {
        return Court::create(['name' => 'San 1', 'status' => 'active', 'price_per_hour' => 100000]);
    }

    public function test_user_can_book_and_price_is_calculated(): void
    {
        $user = $this->makeUser('a@test.com');
        $court = $this->makeCourt();
        $date = now()->addDay()->toDateString();

        $this->actingAs($user)
            ->post("/courts/{$court->id}/book", ['booking_date' => $date, 'start_hour' => 8, 'hours' => 2])
            ->assertRedirect('/my-bookings');

        $this->assertDatabaseHas('bookings', [
            'court_id' => $court->id,
            'user_id' => $user->id,
            'total_price' => 200000,
            'status' => 'pending',
        ]);
    }

    public function test_overlapping_booking_is_rejected_but_adjacent_is_allowed(): void
    {
        $a = $this->makeUser('a@test.com');
        $b = $this->makeUser('b@test.com');
        $court = $this->makeCourt();
        $date = now()->addDay()->toDateString();

        $this->actingAs($a)->post("/courts/{$court->id}/book", ['booking_date' => $date, 'start_hour' => 8, 'hours' => 2]);

        $this->actingAs($b)->post("/courts/{$court->id}/book", ['booking_date' => $date, 'start_hour' => 9, 'hours' => 1])
            ->assertSessionHasErrors('start_hour');
        $this->assertDatabaseCount('bookings', 1);

        $this->actingAs($b)->post("/courts/{$court->id}/book", ['booking_date' => $date, 'start_hour' => 10, 'hours' => 1])
            ->assertRedirect('/my-bookings');
        $this->assertDatabaseCount('bookings', 2);
    }

    public function test_booking_cannot_end_after_22h(): void
    {
        $user = $this->makeUser('a@test.com');
        $court = $this->makeCourt();

        $this->actingAs($user)
            ->post("/courts/{$court->id}/book", [
                'booking_date' => now()->addDay()->toDateString(), 'start_hour' => 21, 'hours' => 2,
            ])->assertSessionHasErrors('hours');
    }

    public function test_user_can_cancel_own_booking_but_not_others(): void
    {
        $a = $this->makeUser('a@test.com');
        $b = $this->makeUser('b@test.com');
        $court = $this->makeCourt();

        $booking = Booking::create([
            'user_id' => $a->id, 'court_id' => $court->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '08:00:00', 'end_time' => '09:00:00',
            'total_price' => 100000, 'status' => 'pending',
        ]);

        $this->actingAs($b)->post("/my-bookings/{$booking->id}/cancel")->assertForbidden();

        $this->actingAs($a)->post("/my-bookings/{$booking->id}/cancel")->assertRedirect('/my-bookings');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'cancelled']);
    }

    public function test_admin_can_confirm_booking(): void
    {
        $admin = $this->makeUser('admin@test.com', 'admin');
        $user = $this->makeUser('a@test.com');
        $court = $this->makeCourt();

        $booking = Booking::create([
            'user_id' => $user->id, 'court_id' => $court->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '08:00:00', 'end_time' => '09:00:00',
            'total_price' => 100000, 'status' => 'pending',
        ]);

        $this->actingAs($admin)->post("/admin/bookings/{$booking->id}/confirm")
            ->assertRedirect('/admin/bookings');

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);
    }
}
