<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama yang terhubung langsung dengan database.
     * Mengimplementasikan PBI #9, #10, #11, dan #21.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi keamanan akun (Fitur Anda)
        if (!$user || !$user->is_active) {
            Auth::logout();
            return redirect()->route('login');
        }

        // 2. Memuat riwayat aktivitas pengguna (Fitur Anda)
        $user->load([
            'logs' => function ($query) {
                $query->latest()->limit(5);
            }
        ]);

        /**
         * 3. PBI #21: Hanya mengambil barang dengan status "available".
         * Kita menggunakan scopeAvailable() yang sudah Anda buat di Model Item.php.
         */
        $query = Item::with(['user', 'category'])->available();

        /**
         * 4. PBI #9: Fitur Pencarian Nama Barang
         */
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        /**
         * 5. PBI #10: Filtering berdasarkan Kategori
         */
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        /**
         * 6. PBI #11: Pengurutan (Sorting)
         */
        if ($request->sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // 7. Eksekusi data
        $items = $query->get();
        
        // Mengambil daftar kategori untuk dropdown filter
        $categories = Category::all();

        // 8. Kirim ke View
        return view('dashboard.dashboard', compact(
            'user',
            'items',
            'categories'
        ));
    }
}