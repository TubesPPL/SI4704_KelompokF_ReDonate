<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Notification;
use App\Models\Category;
use App\Models\Item;
use App\Services\NotificationService;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NotificationTest extends DuskTestCase
{
    public function test_user_can_open_notification_page()
    {
        $user = User::factory()->create([
            'name' => 'User Test Notifikasi',
            'email' => 'notif_' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/notifications')
                ->assertPathIs('/notifications')
                ->assertDontSee('404')
                ->assertDontSee('Server Error');
        });
    }

    public function test_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create([
            'name' => 'User Test Baca Notifikasi',
            'email' => 'read_notif_' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
        ]);

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'request',
            'title' => 'Permintaan Baru',
            'message' => 'Ada permintaan baru pada barang Anda.',
            'data' => [],
            'read_at' => null,
        ]);

        $this->browse(function (Browser $browser) use ($user, $notification) {
            $browser->loginAs($user)
                ->visit('/notifications/' . $notification->id . '/read')
                ->pause(500);
        });

        $notification->refresh();

        $this->assertNotNull($notification->read_at);
    }

    public function test_user_can_delete_notification()
    {
        $user = User::factory()->create([
            'name' => 'User Test Hapus Notifikasi',
            'email' => 'delete_notif_' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
        ]);

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'request',
            'title' => 'Notifikasi Lama',
            'message' => 'Ini notifikasi lama yang akan dihapus.',
            'data' => [],
            'read_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/notifications')
                ->assertSee('Notifikasi Lama')
                ->click('button[title="Hapus Notifikasi"]')
                ->pause(1000)
                ->assertDontSee('Notifikasi Lama');
        });

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id,
        ]);
    }

    public function test_notification_created_when_user_claims_item()
    {
        $this->withoutMiddleware();

        $donor = User::factory()->create([
            'name' => 'Donatur Test',
            'email' => 'donor_' . uniqid() . '@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        $recipient = User::factory()->create([
            'name' => 'Penerima Test',
            'email' => 'recipient_' . uniqid() . '@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        $category = Category::create([
            'name' => 'Kategori Test',
            'slug' => 'kategori-test-' . uniqid(),
            'icon' => null,
            'description' => 'Kategori untuk testing notifikasi.',
        ]);

        $item = Item::create([
            'user_id' => $donor->id,
            'category_id' => $category->id,
            'event_id' => null,
            'title' => 'Barang Test Notifikasi',
            'slug' => 'barang-test-notifikasi-' . uniqid(),
            'description' => 'Barang ini digunakan untuk testing notifikasi otomatis.',
            'condition' => 'good',
            'quantity' => 1,
            'location' => 'Bandung',
            'delivery_method' => 'pickup',
            'status' => 'active',
            'images' => [],
            'views' => 0,
        ]);

        $this->actingAs($recipient)
            ->post(route('claims.store', $item->id), [
                'message' => 'Saya ingin mengajukan klaim untuk barang ini karena sangat membutuhkan.',
                'pickup_date' => now()->addDay()->format('Y-m-d'),
            ]);

        $this->assertDatabaseHas('claims', [
            'item_id' => $item->id,
            'user_id' => $recipient->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $donor->id,
            'type' => NotificationService::CLAIM_RECEIVED,
            'title' => 'Pengajuan Klaim Baru!',
        ]);
    }
}