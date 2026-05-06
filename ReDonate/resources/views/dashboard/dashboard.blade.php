<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ReDonate</title>
    
    <!-- CSS Internal & FontAwesome -->
    @vite(['resources/css/dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Perbaikan CSS untuk Dropdown Klik agar Stabil */
        .profile-menu {
            position: relative;
            display: inline-block;
        }

        #profileDropdown {
            display: none; 
            position: absolute;
            right: 0;
            top: 60px;
            background: white;
            min-width: 240px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid #eee;
            z-index: 9999;
            overflow: hidden;
        }

        /* Mematikan Hover Bawaan agar tidak bentrok */
        .profile-menu:hover .dropdown-content {
            display: none !important;
        }

        .avatar-initial {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 2px solid #16a34a;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .avatar-initial:hover {
            transform: scale(1.05);
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
        }

        .dropdown-link:hover {
            background-color: #f9fafb;
        }

        .dropdown-header {
            padding: 15px;
            background-color: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ url('/dashboard') }}" class="nav-brand">
            <i class="fa-solid fa-heart"></i> ReDonate
        </a>
        
        <div class="nav-links">
            <a href="{{ url('/dashboard') }}">Home</a>
            <a href="#">Barang</a>
            <a href="#">Edukasi</a>
            <a href="#"><i class="fa-solid fa-magnifying-glass"></i> Cari</a>
            <a href="#"><i class="fa-solid fa-chart-simple"></i> Status</a>
        </div>

        <div class="nav-auth">
            <a href="{{ url('/items/create') }}" class="btn-solid" style="margin-right: 15px;">Donasi Sekarang</a>
            
            <div class="profile-menu">
                <!-- Avatar Inisial Nama -->
                <button id="profileBtn" style="background: none; border: none; padding: 0;">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=16a34a&color=fff&bold=true" 
                         alt="{{ auth()->user()->name }}" 
                         class="avatar-initial">
                </button>
                
                <!-- Dropdown Menu -->
                <div id="profileDropdown">
                    <div class="dropdown-header">
                        <p style="font-weight: 700; color: #111827; margin: 0;">{{ auth()->user()->name }}</p>
                        <p style="font-size: 12px; color: #6b7280; margin: 0;">{{ auth()->user()->email }}</p>
                    </div>

                    <a href="/profile" class="dropdown-link">
                        <i class="fa-regular fa-user"></i> Kelola Profil
                    </a>
                    <a href="#" class="dropdown-link">
                        <i class="fa-regular fa-bell"></i> Notifikasi
                    </a>
                    <a href="#" class="dropdown-link">
                        <i class="fa-regular fa-comment-dots"></i> Chat
                    </a>
                    
                    <!-- PBI #14: Fitur Rekan - Daftar Permintaan yang Saya Ajukan (Penerima) -->
                    <a href="{{ route('requests.index') }}" class="dropdown-link">
                        <i class="fa-solid fa-hand-holding-heart" style="color: #16a34a;"></i> 
                        <span style="font-weight: 600;">Permintaan Saya</span>
                    </a>

                    <!-- PBI #17: Permintaan Masuk (Tampilan Donatur) -->
                    <a href="{{ route('donatur.requests.index') }}" class="dropdown-link">
                        <i class="fa-solid fa-inbox"></i> Permintaan Masuk
                    </a>

                    <a href="#" class="dropdown-link">
                        <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Donasi
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" style="border-top: 1px solid #f1f5f9;">
                        @csrf
                        <button type="submit" class="dropdown-link" style="width: 100%; border: none; background: none; cursor: pointer; color: #ef4444; font-weight: 600;">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <section class="section">
        <!-- Notifikasi Berhasil Request -->
        @if(session('success'))
            <div style="margin-bottom: 25px; padding: 15px 20px; background-color: #dcfce7; border: 1px solid #16a34a; color: #166534; border-radius: 12px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:20px;">
            <div class="section-title">
                <h2>Barang Terbaru</h2>
                <p>Temukan barang layak pakai yang baru saja ditambahkan</p>
            </div>
            <a href="#" class="btn-link" style="margin-top:10px;">Lihat Semua &rarr;</a>
        </div>

        <!-- FILTER & SEARCH -->
        <form method="GET" action="{{ route('dashboard') }}" style="margin-bottom:25px;">
            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; padding:15px; background:#f9fafb; border-radius:12px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang..." style="padding:10px 15px; border:1px solid #ddd; border-radius:10px; min-width:220px;">
                
                <select name="category" style="padding:10px 15px; border:1px solid #ddd; border-radius:10px;">
                    <option value="">Semua Kategori</option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    @endif
                </select>

                <select name="sort" style="padding:10px 15px; border:1px solid #ddd; border-radius:10px;">
                    <option value="latest">Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>

                <button type="submit" style="background:#16a34a; color:white; border:none; padding:10px 18px; border-radius:10px; cursor:pointer; font-weight: 600;">Terapkan</button>
                <a href="{{ route('dashboard') }}" style="background:#ef4444; color:white; text-decoration:none; padding:10px 18px; border-radius:10px; font-size: 14px; font-weight: 600;">Reset</a>
            </div>
        </form>
            
        <!-- GRID BARANG -->
        <div class="items-grid">
            @forelse($items as $item)
                <a href="{{ route('items.show', $item->id) }}" class="item-card" style="text-decoration: none; color: inherit; display: block; border: 1px solid #eee; border-radius: 16px; overflow: hidden; background: #fff; transition: transform 0.2s;">
                    <div class="item-img" style="height: 200px; background: #f3f4f6;">
                        @if($item->image_url)
                            <img src="{{ Str::startsWith($item->image_url, ['http', 'https']) ? $item->image_url : asset('storage/' . $item->image_url) }}" 
                                 alt="{{ $item->item_name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #9ca3af;">
                                <i class="fa-regular fa-image fa-2x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="item-content" style="padding: 15px;">
                        <div class="item-header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                            <span class="item-title" style="font-weight: 700; font-size: 16px; color: #111827;">{{ $item->item_name }}</span>
                            <span class="badge-condition" style="background: #f3f4f6; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; color: #4b5563;">{{ ucfirst($item->condition) }}</span>
                        </div>
                        <p class="item-desc" style="font-size: 13px; color: #6b7280; line-height: 1.4; margin-bottom: 15px;">{{ Str::limit($item->description, 70) }}</p>
                        <div class="item-footer" style="display: flex; justify-content: space-between; font-size: 12px; color: #9ca3af; border-top: 1px solid #f9fafb; padding-top: 12px;">
                            <div><i class="fa-solid fa-location-dot" style="color: #16a34a;"></i> {{ Str::limit($item->location, 12) }}</div>
                            <div><i class="fa-regular fa-user"></i> {{ Str::limit($item->user->name ?? 'Anonim', 10) }}</div>
                        </div>
                    </div>
                </a>
            @empty
                <div style="text-align: center; color: #6b7280; grid-column: 1 / -1; padding: 60px 20px;">
                    <i class="fa-solid fa-box-open fa-3x" style="margin-bottom: 15px; opacity: 0.3;"></i>
                    <p>Belum ada barang yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Script Utama -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('profileBtn');
            const dropdown = document.getElementById('profileDropdown');

            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target) && e.target !== btn) {
                    dropdown.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>