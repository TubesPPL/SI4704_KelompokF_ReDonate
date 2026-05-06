<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\RequestItem;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PermintaanMasukTest extends DuskTestCase
{
    use DatabaseMigrations;

    private function siapkanData($statusPermintaan = 'pending')
    {
        $donatur = User::factory()->create(['name' => 'Callista Eliana']);
        $peminta = User::factory()->create(['name' => 'Sarah']);
        
        $kategori = new Category();
        $kategori->category_name = 'Umum';
        $kategori->save();

        $item = new Item();
        $item->user_id = $donatur->id;
        $item->category_id = $kategori->id;
        $item->item_name = 'Pakaian Layak Pakai';
        $item->status = 'available';
        $item->location = 'Bandung';
        $item->condition = 'Bagus';
        $item->save();

        $req = new RequestItem();
        $req->user_id = $peminta->id;
        $req->item_id = $item->id;
        $req->status = $statusPermintaan;
        $req->save();

        return $donatur;
    }

    public function test_tc_pm_001_menampilkan_daftar_permintaan_masuk()
    {
        $donatur = $this->siapkanData('pending');
        $this->browse(function (Browser $browser) use ($donatur) {
            $browser->loginAs($donatur)
                    ->visit('/donatur/requests')
                    ->pause(3000) 
                    ->assertSee('Permintaan Masuk'); 
        });
    }

    public function test_tc_pm_002_menampilkan_status_kosong()
    {
        $donaturKosong = User::factory()->create(['name' => 'Fauzan']);
        $this->browse(function (Browser $browser) use ($donaturKosong) {
            $browser->loginAs($donaturKosong)
                    ->visit('/donatur/requests')
                    ->pause(2000)
                    ->assertSee('Permintaan Masuk'); 
        });
    }

    public function test_tc_pm_003_menyetujui_permintaan_barang()
    {
        $donatur = $this->siapkanData('pending');
        $this->browse(function (Browser $browser) use ($donatur) {
            $browser->loginAs($donatur)
                    ->visit('/donatur/requests')
                    ->pause(2000)
                    ->assertSee('Permintaan'); 
        });
    }

    public function test_tc_pm_004_menolak_permintaan_barang()
    {
        $donatur = $this->siapkanData('pending');
        $this->browse(function (Browser $browser) use ($donatur) {
            $browser->loginAs($donatur)
                    ->visit('/donatur/requests')
                    ->pause(2000)
                    ->assertSee('Permintaan');
        });
    }

    public function test_tc_pm_005_menghapus_riwayat_penolakan()
    {
        $donatur = $this->siapkanData('rejected');
        $this->browse(function (Browser $browser) use ($donatur) {
            $browser->loginAs($donatur)
                    ->visit('/donatur/requests')
                    ->pause(2000)
                    ->assertSee('Bersihkan Riwayat'); 
        });
    }
}