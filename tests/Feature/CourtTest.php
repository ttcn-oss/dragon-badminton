<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourtTest extends TestCase
{
    use RefreshDatabase;

    public function test_courts_page_loads(): void
    {
        $this->get('/courts')->assertOk();
    }

    public function test_can_create_court(): void
    {
        $this->post('/courts', ['name' => 'San 1', 'status' => 'active'])
             ->assertRedirect('/courts');
        $this->assertDatabaseHas('courts', ['name' => 'San 1']);
    }
}
