<?php

namespace Tests\Browser;

use App\Models\Item;
use App\Models\Report;
use App\Models\User;
use App\Models\Notification;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReportTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        // Mengisi data awal agar User dan barang tersedia
        $this->artisan('db:seed');
    }

    /**
     * PBI #41 & #42: Pengguna melaporkan barang
     */
    public function test_user_can_report_an_item(): void
    {
        $this->browse(function (Browser $browser) {
            $reporter = User::where('email', 'user1@redonate.com')->first();
            $item = Item::where('user_id', '!=', $reporter->id)->first();

            $browser->loginAs($reporter)
                    ->visit('/report/create?type=item&id=' . $item->id)
                    ->waitForText('Mengapa Anda melaporkan ini?')
                    ->assertSee('Barang')
                    ->assertSee($item->title)
                    // Klik pada label yang membungkus input radio karena inputnya disembunyikan (sr-only)
                    ->script("document.querySelector('input[value=\"Barang Fiktif/Palsu\"]').parentElement.click();");
                    
            $browser->press('Kirim Laporan')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Laporan berhasil dikirim');

            $this->assertDatabaseHas('reports', [
                'user_id' => $reporter->id,
                'reportable_type' => Item::class,
                'reportable_id' => $item->id,
                'reason' => 'Barang Fiktif/Palsu',
            ]);
        });
    }

    /**
     * PBI #41 & #42: Pengguna melaporkan pengguna lain
     */
    public function test_user_can_report_another_user(): void
    {
        $this->browse(function (Browser $browser) {
            $reporter = User::where('email', 'user1@redonate.com')->first();
            $reportedUser = User::where('email', 'user2@redonate.com')->first();

            $browser->loginAs($reporter)
                    ->visit('/report/create?type=user&id=' . $reportedUser->id)
                    ->waitForText('Mengapa Anda melaporkan ini?')
                    ->assertSee('Pengguna')
                    ->assertSee($reportedUser->name)
                    // Klik pada label yang membungkus input radio
                    ->script("document.querySelector('input[value=\"Spam/Penipuan\"]').parentElement.click();");
                    
            $browser->press('Kirim Laporan')
                    ->waitForLocation('/dashboard')
                    ->assertSee('Laporan berhasil dikirim');

            $this->assertDatabaseHas('reports', [
                'user_id' => $reporter->id,
                'reportable_type' => User::class,
                'reportable_id' => $reportedUser->id,
                'reason' => 'Spam/Penipuan',
            ]);
        });
    }

    /**
     * PBI #44: Admin menerima (resolve) laporan
     */
    public function test_admin_can_resolve_report(): void
    {
        $reporter = User::where('email', 'user1@redonate.com')->first();
        $item = Item::where('user_id', '!=', $reporter->id)->first();
        
        $report = Report::create([
            'user_id' => $reporter->id,
            'reportable_type' => Item::class,
            'reportable_id' => $item->id,
            'reason' => 'Barang Fiktif/Palsu',
            'status' => 'pending'
        ]);

        $this->browse(function (Browser $browser) use ($report) {
            $admin = User::where('email', 'admin@redonate.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->id)
                    ->waitForText('Detail Laporan #')
                    ->assertSee('Barang Fiktif/Palsu')
                    ->select('status', 'resolved')
                    ->select('action', 'suspend_item')
                    ->type('admin_notes', 'Tindakan diambil karena terbukti melanggar.')
                    ->press('Simpan Keputusan')
                    // Menerima dialog konfirmasi Javascript (onsubmit="return confirm(...)")
                    ->acceptDialog()
                    ->waitForLocation('/admin/reports')
                    ->assertSee('Keputusan laporan berhasil disimpan');

            $this->assertDatabaseHas('reports', [
                'id' => $report->id,
                'status' => 'resolved',
            ]);
        });
    }

    /**
     * PBI #44: Admin menolak (reject) laporan
     */
    public function test_admin_can_reject_report(): void
    {
        $reporter = User::where('email', 'user1@redonate.com')->first();
        $reportedUser = User::where('email', 'user2@redonate.com')->first();
        
        $report = Report::create([
            'user_id' => $reporter->id,
            'reportable_type' => User::class,
            'reportable_id' => $reportedUser->id,
            'reason' => 'Akun Palsu',
            'status' => 'pending'
        ]);

        $this->browse(function (Browser $browser) use ($report) {
            $admin = User::where('email', 'admin@redonate.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->id)
                    ->waitForText('Detail Laporan #')
                    ->assertSee('Akun Palsu')
                    ->select('status', 'rejected')
                    ->select('action', 'no_action')
                    ->type('admin_notes', 'Tidak ada bukti pelanggaran.')
                    ->press('Simpan Keputusan')
                    // Menerima dialog konfirmasi
                    ->acceptDialog()
                    ->waitForLocation('/admin/reports')
                    ->assertSee('Keputusan laporan berhasil disimpan');

            $this->assertDatabaseHas('reports', [
                'id' => $report->id,
                'status' => 'rejected',
            ]);
        });
    }

    /**
     * PBI #43: Pengguna menerima notifikasi setelah laporan diproses
     */
    public function test_user_receives_notification_after_report_resolved(): void
    {
        $reporter = User::where('email', 'user1@redonate.com')->first();
        $item = Item::where('user_id', '!=', $reporter->id)->first();
        
        $report = Report::create([
            'user_id' => $reporter->id,
            'reportable_type' => Item::class,
            'reportable_id' => $item->id,
            'reason' => 'Barang Fiktif/Palsu',
            'status' => 'pending'
        ]);

        $this->browse(function (Browser $adminBrowser, Browser $userBrowser) use ($reporter, $report) {
            $admin = User::where('email', 'admin@redonate.com')->first();

            // Admin resolves the report
            $adminBrowser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->id)
                    ->waitForText('Detail Laporan #')
                    ->select('status', 'resolved')
                    ->select('action', 'suspend_item')
                    ->type('admin_notes', 'Terbukti melanggar.')
                    ->press('Simpan Keputusan')
                    ->acceptDialog()
                    ->waitForLocation('/admin/reports');

            // Reporter checks notification
            $userBrowser->loginAs($reporter)
                    ->visit('/notifications')
                    ->waitForText('Laporan Ditindaklanjuti')
                    ->assertSee('Laporan Anda terkait barang telah ditinjau dan ditindaklanjuti');
        });
    }
}
