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
            <a href="#" class="btn-solid">Donasi Sekarang</a>
            
            <!-- Menu Profil & Panel PBI 17 -->
            <div class="profile-menu">
                <img src="{{ auth()->check() ? auth()->user()->photo_url : asset('images/default-avatar.png') }}" alt="User Avatar" class="avatar-small" onerror="this.src='https://ui-avatars.com/api/?name=User&background=0D8ABC&color=fff'">
                
                <div class="dropdown-content">
                    <a href="/profile"><i class="fa-regular fa-user"></i> Kelola Profil</a>
                    
                    <!-- Fitur Notifikasi dan Chat yang dipindah ke dalam dropdown -->
                    <a href="#"><i class="fa-regular fa-bell"></i> Notifikasi</a>
                    <a href="#"><i class="fa-regular fa-comment-dots"></i> Chat</a>
                    
                    <!-- Ini adalah gerbang masuk ke PBI 17 -->
                    <a href="{{ route('donatur.requests.index') ?? '#' }}"><i class="fa-solid fa-inbox"></i> Permintaan Masuk</a>
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
                @if(isset($categories))
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                @endif
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
            
    <!-- GRID BARANG -->
    <div class="items-grid">
        @if(isset($items) && $items->count() > 0)
            @foreach($items as $item)
                <a href="{{ url('/items/' . $item->id) }}" class="item-card" style="text-decoration: none; color: inherit; display: block;">
                    
                    <!-- BAGIAN GAMBAR YANG SUDAH DIPERBAIKI -->
                    <div class="item-img">
                        @if($item->image_url)
                            @if(Str::startsWith($item->image_url, ['http://', 'https://']))
                                <img src="{{ $item->image_url }}" alt="{{ $item->item_name }}" style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->item_name }}" style="width: 100%; height: 200px; object-fit: cover;">
                            @endif
                        @else
                            <img src="https://placehold.co/400x300/e2e8f0/475569?text=No+Image" alt="No Image" style="width: 100%; height: 200px; object-fit: cover;">
                        @endif
                    </div>

                    <div class="item-content">
                        <div class="item-header">
                            <span class="item-title">{{ $item->item_name }}</span>
                            <span class="badge-condition">{{ ucfirst($item->condition) }}</span>
                        </div>

                        <p class="item-desc">{{ Str::limit($item->description, 100) }}</p>

                        <div class="item-footer">
                            <div><i class="fa-solid fa-location-dot"></i> {{ $item->location ?? 'Lokasi tidak diketahui' }}</div>
                            <div><i class="fa-regular fa-user"></i> {{ $item->user->name ?? 'Donatur' }}</div>
                            <div><i class="fa-regular fa-calendar"></i> {{ $item->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <p style="text-align: center; color: #6b7280; grid-column: 1 / -1; padding: 20px;">Belum ada barang yang tersedia saat ini.</p>
        @endif
    </div>
   </section>

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