<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\User;
use App\Models\RequestItem;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RequestItemTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        // Mengisi data awal agar User ID 2 dan barang tersedia
        $this->artisan('db:seed');
    }

    /** @test */
    public function test_penerima_can_request_and_manage_item(): void
    {
        $this->browse(function (Browser $browser) {
            $item = Item::where('status', 'available')->first();
            $penerima = User::find(2); 

            $browser->loginAs($penerima)
                    ->visit('/items/' . $item->id)
                    ->waitForText($item->item_name)
                    // PBI 13 & 14: Submit & List
                    ->clickLink('Ajukan Permintaan')
                    ->type('message', 'Halo, saya membutuhkan barang ini. [PBI 13-14]')
                    ->select('pickup_method', 'kurir')
                    ->press('Kirim Permintaan Sekarang')
                    ->waitForLocation('/my-requests')
                    ->assertSee('Permintaan barang berhasil diajukan!')
                    
                    // PBI 15: Update Preferensi
                    ->click('a[title="Ubah Preferensi"]')
                    ->waitForText('Ubah Metode Pengambilan')
                    ->select('pickup_method', 'cod')
                    ->press('Simpan Perubahan')
                    ->waitForText('Metode pengambilan berhasil diperbarui!')
                    ->assertSee('Bertemu Langsung (COD)')

                    // PBI 16: Pembatalan
                    ->press('Batalkan Permintaan')
                    ->acceptDialog()
                    ->waitForText('Permintaan berhasil dibatalkan.');
        });
    }
}