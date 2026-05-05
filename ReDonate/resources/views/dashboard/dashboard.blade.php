<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ReDonate</title>
    
    @vite(['resources/css/dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Navbar Logged In -->
    <nav class="navbar">
        <a href="/" class="nav-brand">
            <i class="fa-solid fa-heart"></i> ReDonate
        </a>
        
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#">Barang</a>
            <a href="#">Edukasi</a>
            <!-- Fitur Sprint 1 yang tersisa di luar -->
            <a href="#"><i class="fa-solid fa-magnifying-glass"></i> Cari</a>
            <a href="#"><i class="fa-solid fa-chart-simple"></i> Status</a>
        </div>

        <div class="nav-auth">
            <a href="{{ route('items.create') }}" class="btn-solid">Donasi Sekarang</a>
            
            <!-- Menu Profil & Panel PBI 17 -->
            <div class="profile-menu">
                <img src="{{ auth()->check() ? auth()->user()->photo_url : asset('images/default-avatar.png') }}" alt="User Avatar" class="avatar-small" onerror="this.src='https://ui-avatars.com/api/?name=User&background=0D8ABC&color=fff'">
                
                <div class="dropdown-content">
                    <a href="/profile"><i class="fa-regular fa-user"></i> Kelola Profil</a>
                    
                    <!-- Fitur Notifikasi dan Chat yang dipindah ke dalam dropdown -->
                    <a href="#"><i class="fa-regular fa-bell"></i> Notifikasi</a>
                    <a href="#"><i class="fa-regular fa-comment-dots"></i> Chat</a>
                    
                    <!-- Ini adalah gerbang masuk ke PBI 17 Anda -->
                    <a href="{{ route('donatur.requests.index') }}"><i class="fa-solid fa-inbox"></i> Permintaan Masuk</a>
                    <a href="#"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Donasi</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Section Barang Terbaru -->
   <section class="section">

    <!-- HEADER -->
    <div class="section-header" style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        margin-bottom:20px;
    ">

        <div class="section-title">
            <h2>Barang Terbaru</h2>
            <p>Temukan barang layak pakai yang baru saja ditambahkan</p>
        </div>

        <a href="#" class="btn-link" style="margin-top:10px;">
            Lihat Semua &rarr;
        </a>
    </div>

    <!-- FILTER -->
    <form method="GET" action="{{ route('dashboard') }}" style="margin-bottom:25px;">
        <div style="
            display:flex;
            gap:12px;
            align-items:center;
            flex-wrap:wrap;
            padding:15px;
            background:#f9fafb;
            border-radius:12px;
        ">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari barang..."
                style="padding:10px 15px; border:1px solid #ddd; border-radius:10px; min-width:220px;"
            >

            <select name="category" style="padding:10px 15px; border:1px solid #ddd; border-radius:10px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}"
                        {{ request('category') == $category->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>

            <select name="sort" style="padding:10px 15px; border:1px solid #ddd; border-radius:10px;">
                <option value="latest">Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                    Terlama
                </option>
            </select>

            <button type="submit" style="
                background:#16a34a;
                color:white;
                border:none;
                padding:10px 18px;
                border-radius:10px;
                cursor:pointer;
            ">
                Terapkan
            </button>

            <a href="{{ route('dashboard') }}" style="
                background:#ef4444;
                color:white;
                text-decoration:none;
                padding:10px 18px;
                border-radius:10px;
            ">
                Reset
            </a>

        </div>
    </form>

    <!-- GRID BARANG  -->
            
        <!-- GRID BARANG -->
<div class="items-grid">

    @php
        $dummyItems = [
            [
                'id' => 1,
                'name' => 'Sofa 3 Dudukan',
                'condition' => 'Baik',
                'description' => 'Sofa dalam kondisi sangat baik, warna coklat, nyaman untuk keluarga.',
                'location' => 'Jakarta Selatan',
                'user' => 'Budi Santoso',
                'date' => '2026-03-28',
                'category' => 3,
                'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&q=80&w=400',
            ],
            [
                'id' => 2,
                'name' => 'Pakaian Anak',
                'condition' => 'Baik',
                'description' => 'Koleksi pakaian anak usia 5-10 tahun, kondisi masih bagus dan bersih.',
                'location' => 'Bandung',
                'user' => 'Siti Rahayu',
                'date' => '2026-03-29',
                'category' => 1,
                'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&q=80&w=400',
            ],
            [
                'id' => 3,
                'name' => 'Buku Pelajaran SMA',
                'condition' => 'Cukup Baik',
                'description' => 'Kumpulan buku pelajaran SMA berbagai mata pelajaran.',
                'location' => 'Surabaya',
                'user' => 'Ahmad Wijaya',
                'date' => '2026-03-27',
                'category' => 4,
                'image' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=400',
            ],
            [
                'id' => 4,
                'name' => 'Laptop untuk Belajar',
                'condition' => 'Baik',
                'description' => 'Laptop bekas masih berfungsi baik untuk belajar online.',
                'location' => 'Yogyakarta',
                'user' => 'Dewi Lestari',
                'date' => '2026-03-30',
                'category' => 2,
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&q=80&w=400',
            ],
        ];

        // SEARCH
        if(request('search')){
            $dummyItems = array_filter($dummyItems, function($item){
                return str_contains(strtolower($item['name']), strtolower(request('search')));
            });
        }

        // CATEGORY
        if(request('category')){
            $dummyItems = array_filter($dummyItems, function($item){
                return $item['category'] == request('category');
            });
        }

        // SORT
        usort($dummyItems, function($a, $b){
            if(request('sort') == 'oldest'){
                return strtotime($a['date']) <=> strtotime($b['date']);
            }
            return strtotime($b['date']) <=> strtotime($a['date']);
        });
    @endphp

    @foreach($dummyItems as $item)
        <a href="/items/{{ $item['id'] ?? 1 }}" style="text-decoration: none; color: inherit;">
            <div class="item-card">
                <div class="item-img">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                </div>

                <div class="item-content">
                    <div class="item-header">
                        <span class="item-title">{{ $item['name'] }}</span>
                        <span class="badge-condition">{{ $item['condition'] }}</span>
                    </div>

                    <p class="item-desc">{{ $item['description'] }}</p>

                    <div class="item-footer">
                        <div><i class="fa-solid fa-location-dot"></i> {{ $item['location'] }}</div>
                        <div><i class="fa-regular fa-user"></i> {{ $item['user'] }}</div>
                        <div><i class="fa-regular fa-calendar"></i> {{ date('d/m/Y', strtotime($item['date'])) }}</div>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

</div>
    <!-- Section Kategori -->
    <section class="category-section">
        <h2>Kategori Barang</h2>
        <p>Temukan barang berdasarkan kategori</p>
        
        <div class="category-grid">
            <a href="#" class="category-card"><span class="category-icon">🛋️</span>Furnitur</a>
            <a href="#" class="category-card"><span class="category-icon">👕</span>Pakaian</a>
            <a href="#" class="category-card"><span class="category-icon">📚</span>Buku</a>
            <a href="#" class="category-card"><span class="category-icon">💻</span>Elektronik</a>
        </div>
    </section>

</body>
</html>