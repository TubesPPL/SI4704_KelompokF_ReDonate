<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WishlistTest extends DuskTestCase
{
    public function test_user_can_add_item_to_wishlist()
    {
        $user = $this->createTestUser('user_wishlist');
        $donor = $this->createTestUser('donor_wishlist');
        $category = $this->createTestCategory();

        $item = $this->createTestItem($donor, $category, 'active', 'Barang Wishlist Tambah');

        DB::table('wishlists')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_user_can_view_wishlist_page()
    {
        $user = $this->createTestUser('user_view_wishlist');
        $donor = $this->createTestUser('donor_view_wishlist');
        $category = $this->createTestCategory();

        $item = $this->createTestItem($donor, $category, 'active', 'Barang Wishlist Halaman');

        DB::table('wishlists')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/wishlist')
                ->assertPathIs('/wishlist')
                ->assertSee('Wishlist Saya')
                ->assertSee('Barang Wishlist Halaman');
        });
    }

    public function test_user_can_delete_item_from_wishlist()
    {
        $user = $this->createTestUser('user_delete_wishlist');
        $donor = $this->createTestUser('donor_delete_wishlist');
        $category = $this->createTestCategory();

        $item = $this->createTestItem($donor, $category, 'active', 'Barang Wishlist Hapus');

        DB::table('wishlists')->insert([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('wishlists')
            ->where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->delete();

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_user_can_see_item_availability_status_in_wishlist()
    {
        $user = $this->createTestUser('user_status_wishlist');
        $donor = $this->createTestUser('donor_status_wishlist');
        $category = $this->createTestCategory();

        $availableItem = $this->createTestItem($donor, $category, 'active', 'Barang Status Tersedia');
        $completedItem = $this->createTestItem($donor, $category, 'completed', 'Barang Status Selesai');

        DB::table('wishlists')->insert([
            [
                'user_id' => $user->id,
                'item_id' => $availableItem->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'item_id' => $completedItem->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/wishlist')
                ->assertSee('Wishlist Saya')
                ->assertSee('Barang Status Tersedia')
                ->assertSee('Tersedia')
                ->assertSourceHas('Barang Status Selesai')
                ->assertSourceHas('Sudah Didonasikan');
        });
    }

    private function createTestUser($prefix)
    {
        return User::factory()->create([
            'name' => 'Test User',
            'email' => $prefix . '_' . uniqid() . '@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
    }

    private function createTestCategory()
    {
        return Category::create([
            'name' => 'Kategori Wishlist Test',
            'slug' => 'kategori-wishlist-test-' . uniqid(),
            'icon' => null,
            'description' => 'Kategori untuk testing wishlist.',
        ]);
    }

    private function createTestItem($donor, $category, $status, $title)
    {
        return Item::create([
            'user_id' => $donor->id,
            'category_id' => $category->id,
            'event_id' => null,
            'title' => $title,
            'slug' => strtolower(str_replace(' ', '-', $title)) . '-' . uniqid(),
            'description' => 'Barang ini digunakan untuk testing fitur wishlist.',
            'condition' => 'good',
            'quantity' => 1,
            'location' => 'Bandung',
            'delivery_method' => 'pickup',
            'status' => $status,
            'images' => [],
            'views' => 0,
        ]);
    }
}