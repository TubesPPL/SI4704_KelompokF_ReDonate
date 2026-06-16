<?php

namespace Tests\Browser;

use App\Models\Claim;
use App\Models\Item;
use App\Models\Review;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Laravel Dusk Browser Tests untuk fitur Ulasan & Rating
 */
class ReviewTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Helper: Setup data lengkap untuk transaksi yang telah selesai.
     */
    protected function createCompletedClaimSetup(): array
    {
        $category = Category::factory()->create();

        $donor = User::factory()->create([
            'name'     => 'Donatur Test',
            'email'    => 'donor@test.com',
            'password' => bcrypt('password'),
        ]);

        $recipient = User::factory()->create([
            'name'     => 'Penerima Test',
            'email'    => 'penerima@test.com',
            'password' => bcrypt('password'),
        ]);

        $item = Item::factory()->create([
            'user_id'     => $donor->id,
            'category_id' => $category->id,
            'title'       => 'Buku Bekas Layak Pakai',
            'status'      => 'completed',
        ]);

        $claim = Claim::factory()->create([
            'item_id'     => $item->id,
            'user_id'     => $recipient->id,
            'status'      => 'completed',
            'pickup_date' => '2026-06-16', 
            'notes'       => 'Catatan aman',
        ]);

        return [$donor, $recipient, $claim];
    }

    /**
     * PBI #37: Memberikan rating dan ulasan setelah transaksi selesai.
     */
    public function test_pbi37_submit_review(): void
    {
        [$donor, $recipient, $claim] = $this->createCompletedClaimSetup();

        $this->browse(function (Browser $browser) use ($recipient, $claim) {
            $browser->loginAs($recipient)
                    ->visit(route('reviews.create', $claim->id))
                    ->assertSee('Beri Ulasan Donatur');
            
            $browser->script("document.querySelector('input[name=\"rating\"]').value = 5;
                               document.querySelector('input[name=\"rating\"]').dispatchEvent(new Event('input'));");

            $browser->type('comment', 'Barang sangat bagus dan pelayanan cepat!')
                    ->press('Kirim Ulasan')
                    ->pause(2000)
                    ->assertPathIs('/recipient/dashboard');
        });

        $this->assertDatabaseHas('reviews', [
            'claim_id'    => $claim->id,
            'reviewer_id' => $recipient->id,
            'reviewee_id' => $donor->id,
            'rating'      => 5,
            'comment'     => 'Barang sangat bagus dan pelayanan cepat!',
        ]);
    }

    /**
     * PBI #38: Melihat rata-rata rating dan daftar ulasan di profil pengguna.
     */
    public function test_pbi38_view_profile_rating(): void
    {
        [$donor, $recipient, $claim] = $this->createCompletedClaimSetup();

        Review::factory()->create([
            'claim_id'    => $claim->id,
            'reviewer_id' => $recipient->id,
            'reviewee_id' => $donor->id,
            'rating'      => 5,
            'comment'     => 'Donatur terpercaya!',
        ]);

        $this->browse(function (Browser $browser) use ($donor) {
            $browser->visit(route('profile.show', $donor->id))
                    ->assertSee('5.0')
                    ->assertSee('Donatur terpercaya!');
        });
    }

    /**
     * PBI #39: Mengedit atau menghapus ulasan yang sudah diberikan.
     */
    public function test_pbi39_edit_review(): void
    {
        [$donor, $recipient, $claim] = $this->createCompletedClaimSetup();

        $review = Review::factory()->create([
            'claim_id'    => $claim->id,
            'reviewer_id' => $recipient->id,
            'reviewee_id' => $donor->id,
            'rating'      => 3,
            'comment'     => 'Komentar lama.',
        ]);

        $this->browse(function (Browser $browser) use ($recipient, $review, $donor) {
            $browser->loginAs($recipient)
                    ->visit(route('reviews.edit', $review->id));
            
            $browser->script("document.querySelector('input[name=\"rating\"]').value = 5;
                               document.querySelector('input[name=\"rating\"]').dispatchEvent(new Event('input'));");

            $browser->clear('comment')
                    ->type('comment', 'Komentar baru hasil edit.')
                    ->press('Simpan Perubahan')
                    ->pause(2000);
        });

        $this->assertDatabaseHas('reviews', [
            'id'      => $review->id,
            'rating'  => 5,
            'comment' => 'Komentar baru hasil edit.',
        ]);
    }

    /**
     * PBI #40: Membalas ulasan yang diterima.
     */
    public function test_pbi40_reply_to_review(): void
    {
        [$donor, $recipient, $claim] = $this->createCompletedClaimSetup();

        $review = Review::factory()->create([
            'claim_id'    => $claim->id,
            'reviewer_id' => $recipient->id,
            'reviewee_id' => $donor->id,
            'rating'      => 5,
            'comment'     => 'Donatur terbaik!',
            'reply'       => null,
        ]);

        $this->browse(function (Browser $browser) use ($donor) {
            $browser->loginAs($donor)
                    ->visit(route('profile.show', $donor->id));

            // KUNCI UTAMA: Buka paksa container form reply jika disembunyikan oleh sistem CSS/JS bawaan template
            $browser->script("
                var replyForm = document.querySelector('form[action*=\"reply\"], form[action*=\"review\"]');
                if (replyForm) {
                    replyForm.style.display = 'block';
                    replyForm.style.visibility = 'visible';
                }
                
                // Cari tombol balas yang valid di level interaktif (bukan div/span sembarangan)
                var buttons = document.querySelectorAll('button, a, input[type=\"button\"]');
                for (var i = 0; i < buttons.length; i++) {
                    if (buttons[i].textContent.trim().toLowerCase().includes('balas')) {
                        buttons[i].scrollIntoView({ block: 'center' });
                        buttons[i].click();
                        break;
                    }
                }
            ");

            // Gunakan interaksi hibrida: tunggu selektor atau bypass langsung via injeksi DOM jika macet
            $browser->pause(1500)
                    ->script("
                        var textarea = document.querySelector('textarea[name=\"reply\"], textarea[id*=\"reply\"]');
                        if(textarea) {
                            textarea.scrollIntoView({ block: 'center' });
                            textarea.value = 'Terima kasih kembali, senang bisa membantu!';
                            textarea.dispatchEvent(new Event('input', { bubbles: true }));
                            textarea.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    ");

            // Tekan submit secara presisi mencari tombol kirim di dalam lingkup form tanggapan
            $browser->script("
                var submitBtn = document.querySelector('form button[type=\"submit\"], button[id*=\"submit\"], input[value*=\"Balasan\"]');
                if(submitBtn) {
                    submitBtn.click();
                } else {
                    var forms = document.getElementsByTagName('form');
                    for(var i=0; i<forms.length; i++) {
                        if(forms[i].innerHTML.includes('reply')) { forms[i].submit(); break; }
                    }
                }
            ");
            
            $browser->pause(2000);
        });

        $this->assertDatabaseHas('reviews', [
            'id'    => $review->id,
            'reply' => 'Terima kasih kembali, senang bisa membantu!',
        ]);
    }
}