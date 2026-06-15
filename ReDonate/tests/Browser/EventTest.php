<?php

namespace Tests\Browser;

use App\Models\Event;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EventTest extends DuskTestCase
{
    public function test_admin_can_create_donation_event()
    {
        $admin = $this->createTestUser('admin_event');

        Event::create([
            'created_by' => $admin->id,
            'title' => 'Event Donasi Test',
            'slug' => 'event-donasi-test-' . uniqid(),
            'description' => 'Event ini dibuat untuk kebutuhan testing fitur event donasi.',
            'banner' => null,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(7)->format('Y-m-d'),
            'target_items' => 20,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Event Donasi Test',
            'status' => 'active',
            'target_items' => 20,
        ]);
    }

    public function test_user_can_view_active_donation_events()
    {
        $user = $this->createTestUser('user_event_active');
        $admin = $this->createTestUser('admin_event_active');

        $this->createTestEvent($admin, 'Event Donasi Aktif', 'active');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/events')
                ->assertPathIs('/events')
                ->assertSee('Event Donasi Aktif');
        });
    }

    public function test_user_can_view_donation_event_detail()
    {
        $user = $this->createTestUser('user_event_detail');
        $admin = $this->createTestUser('admin_event_detail');

        $event = $this->createTestEvent($admin, 'Event Detail Donasi', 'active');

        $this->browse(function (Browser $browser) use ($user, $event) {
            $browser->loginAs($user)
                ->visit('/events/' . $event->slug)
                ->assertPathIs('/events/' . $event->slug)
                ->assertSee('Event Detail Donasi')
                ->assertSee('Event ini digunakan untuk testing fitur event donasi.');
        });
    }

    public function test_user_can_view_completed_donation_event()
    {
        $user = $this->createTestUser('user_event_completed');
        $admin = $this->createTestUser('admin_event_completed');

        $event = $this->createTestEvent($admin, 'Event Donasi Selesai', 'completed');

        $this->browse(function (Browser $browser) use ($user, $event) {
            $browser->loginAs($user)
                ->visit('/events/' . $event->slug)
                ->assertPathIs('/events/' . $event->slug)
                ->assertSee('Event Donasi Selesai')
                ->assertSee('Event ini digunakan untuk testing fitur event donasi.');
        });
    }

    private function createTestUser($prefix)
    {
        return User::factory()->create([
            'name' => 'Test User Event',
            'email' => $prefix . '_' . uniqid() . '@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
    }

    private function createTestEvent($admin, $title, $status)
    {
        return Event::create([
            'created_by' => $admin->id,
            'title' => $title,
            'slug' => strtolower(str_replace(' ', '-', $title)) . '-' . uniqid(),
            'description' => 'Event ini digunakan untuk testing fitur event donasi.',
            'banner' => null,
            'start_date' => now()->subDay()->format('Y-m-d'),
            'end_date' => now()->addDays(7)->format('Y-m-d'),
            'target_items' => 20,
            'status' => $status,
        ]);
    }
}