<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Claim;
use App\Models\Message;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Membantu membuat setup data standar (Donor, Penerima, Item, dan Claim)
     */
    private function setupChatEnvironment()
    {
        $donor = User::factory()->create(['name' => 'Budi Donatur']);
        $recipient = User::factory()->create(['name' => 'Andi Penerima']);
        
        $item = Item::factory()->create([
            'user_id' => $donor->id,
            'title' => 'Buku Pemrograman Laravel',
        ]);
        
        $claim = Claim::factory()->create([
            'user_id' => $recipient->id,
            'item_id' => $item->id,
            'status' => 'approved',
        ]);

        return [$donor, $recipient, $item, $claim];
    }

    /**
     * TC-CHAT-01: Mengirim pesan obrolan (Positif)
     * Referensi: PBI #25
     */
    public function test_tc_chat_01_mengirim_pesan_valid()
    {
        [$donor, $recipient, $item, $claim] = $this->setupChatEnvironment();

        $response = $this->actingAs($recipient)->postJson("/chat/{$claim->id}", [
            'body' => 'Halo Pak Budi, saya bisa ambil donasinya besok?',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]); 
        
        $this->assertDatabaseHas('messages', [
            'body' => 'Halo Pak Budi, saya bisa ambil donasinya besok?',
            'claim_id' => $claim->id,
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $donor->id,
            'type' => 'new_message',
        ]);
    }

    /**
     * TC-CHAT-02: Mengirim pesan obrolan (Negatif)
     * Referensi: PBI #25
     */
    public function test_tc_chat_02_menolak_pesan_kosong()
    {
        [$donor, $recipient, $item, $claim] = $this->setupChatEnvironment();

        $response = $this->actingAs($recipient)->postJson("/chat/{$claim->id}", [
            'body' => '',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['body']);
        $this->assertDatabaseCount('messages', 0);
    }
    
    /**
     * TC-CHAT-03: Memuat halaman obrolan (Positif)
     * Referensi: PBI #26
     */
    public function test_tc_chat_03_memuat_halaman_chat_perdana()
    {
        [$donor, $recipient, $item, $claim] = $this->setupChatEnvironment();

        $response = $this->actingAs($donor)->get("/chat/{$claim->id}");

        $response->assertStatus(200);
        $response->assertSee('Belum ada pesan. Sapa Andi Penerima sekarang!');
    }

    /**
     * TC-CHAT-04: Memperbarui status baca (Positif)
     * Referensi: PBI #27
     */
    public function test_tc_chat_04_pembaruan_otomatis_status_baca()
    {
        [$donor, $recipient, $item, $claim] = $this->setupChatEnvironment();
        
        $message = Message::create([
            'claim_id' => $claim->id,
            'sender_id' => $donor->id,
            'body' => 'Silakan ambil di rumah saya ya.',
            'read_at' => null, 
        ]);

        $response = $this->actingAs($recipient)->get("/chat/{$claim->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('messages', [
            'id' => $message->id,
            'read_at' => null,
        ]);
    }

    /**
     * TC-CHAT-05: Mengambil pesan terbaru (Positif)
     * Referensi: PBI #26
     */
    public function test_tc_chat_05_polling_pesan_baru()
    {
        [$donor, $recipient, $item, $claim] = $this->setupChatEnvironment();
        
        Message::create([
            'claim_id' => $claim->id,
            'sender_id' => $donor->id,
            'body' => 'Pesan pertama',
            'read_at' => now(),
        ]);

        Message::create([
            'claim_id' => $claim->id,
            'sender_id' => $donor->id,
            'body' => 'Pesan baru via polling',
            'read_at' => null,
        ]);

        $response = $this->actingAs($recipient)->getJson("/chat/{$claim->id}/poll?last_id=1");

        $response->assertStatus(200);
        $response->assertJsonFragment(['body' => 'Pesan baru via polling']);
    }

    /**
     * TC-CHAT-06: Menghapus obrolan
     * Referensi: PBI #28
     */
    public function test_tc_chat_06_menghapus_obrolan_via_notifikasi()
    {
        [$donor, $recipient, $item, $claim] = $this->setupChatEnvironment();
        
        // 1. mengirim pesan
        $this->actingAs($recipient)->postJson("/chat/{$claim->id}", [
            'body' => 'Halo Pak, saya mau ambil barangnya.',
        ]);

        // Pastikan notifikasi berhasil masuk menggunakan kolom user_id
        $this->assertDatabaseHas('notifications', [
            'user_id' => $donor->id,
        ]);

        // Ambil ID notifikasi yang baru saja dibuat
        $notification = \Illuminate\Support\Facades\DB::table('notifications')
                            ->where('user_id', $donor->id)
                            ->first();

        // 2. Budi menekan tombol tempat sampah
        $this->actingAs($donor)->delete("/notifications/{$notification->id}");

        // 3
        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id
        ]);
    }
}