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
        <div class="section-header">
            <div class="section-title">
                <h2>Barang Terbaru</h2>
                <p>Temukan barang layak pakai yang baru saja ditambahkan</p>
            </div>
            <a href="#" class="btn-link">Lihat Semua &rarr;</a>
        </div>

        <div class="items-grid">
            <!-- Item 1 -->
            <div class="item-card">
                <div class="item-img">
                    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&q=80&w=400" alt="Sofa">
                </div>
                <div class="item-content">
                    <div class="item-header">
                        <span class="item-title">Sofa 3 Dudukan</span>
                        <span class="badge-condition">Baik</span>
                    </div>
                    <p class="item-desc">Sofa dalam kondisi sangat baik, warna coklat, nyaman untuk keluarga. Hanya ada sedikit goresan di bagian kaki belakang.</p>
                    <div class="item-footer">
                        <div><i class="fa-solid fa-location-dot"></i> Jakarta Selatan</div>
                        <div style="display: flex; justify-content: space-between; width: 100%;">
                            <span><i class="fa-regular fa-user"></i> Budi Santoso</span>
                            <span style="color:#fbbf24;"><i class="fa-solid fa-star"></i> 4.8</span>
                        </div>
                        <div><i class="fa-regular fa-calendar"></i> 28/3/2026</div>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="item-card">
                <div class="item-img">
                    <span class="badge-verified"><i class="fa-solid fa-shield-check"></i> Verified</span>
                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&q=80&w=400" alt="Pakaian">
                </div>
                <div class="item-content">
                    <div class="item-header">
                        <span class="item-title">Pakaian Anak</span>
                        <span class="badge-condition">Baik</span>
                    </div>
                    <p class="item-desc">Koleksi pakaian anak usia 5-10 tahun, kondisi masih bagus dan bersih. Cocok untuk dipakai sehari-hari.</p>
                    <div class="item-footer">
                        <div><i class="fa-solid fa-location-dot"></i> Bandung</div>
                        <div style="display: flex; justify-content: space-between; width: 100%;">
                            <span><i class="fa-regular fa-user"></i> Siti Rahayu</span>
                            <span style="color:#fbbf24;"><i class="fa-solid fa-star"></i> 5.0</span>
                        </div>
                        <div><i class="fa-regular fa-calendar"></i> 29/3/2026</div>
                    </div>
                </div>
            </div>
            
            <!-- Item 3 -->
            <div class="item-card">
                <div class="item-img">
                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=400" alt="Buku">
                </div>
                <div class="item-content">
                    <div class="item-header">
                        <span class="item-title">Buku Pelajaran SMA</span>
                        <span class="badge-condition" style="background:#fef3c7; color:#d97706;">Cukup Baik</span>
                    </div>
                    <p class="item-desc">Kumpulan buku pelajaran untuk SMA kelas 10-12, berbagai mata pelajaran mulai dari Matematika hingga Sejarah.</p>
                    <div class="item-footer">
                        <div><i class="fa-solid fa-location-dot"></i> Surabaya</div>
                        <div style="display: flex; justify-content: space-between; width: 100%;">
                            <span><i class="fa-regular fa-user"></i> Ahmad Wijaya</span>
                            <span style="color:#fbbf24;"><i class="fa-solid fa-star"></i> 4.5</span>
                        </div>
                        <div><i class="fa-regular fa-calendar"></i> 27/3/2026</div>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="item-card">
                <div class="item-img">
                    <span class="badge-verified"><i class="fa-solid fa-shield-check"></i> Verified</span>
                    <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&q=80&w=400" alt="Laptop">
                </div>
                <div class="item-content">
                    <div class="item-header">
                        <span class="item-title">Laptop untuk Belajar</span>
                        <span class="badge-condition">Baik</span>
                    </div>
                    <p class="item-desc">Laptop bekas masih berfungsi dengan baik, cocok untuk belajar online dan mengerjakan tugas sekolah anak.</p>
                    <div class="item-footer">
                        <div><i class="fa-solid fa-location-dot"></i> Yogyakarta</div>
                        <div style="display: flex; justify-content: space-between; width: 100%;">
                            <span><i class="fa-regular fa-user"></i> Dewi Lestari</span>
                            <span style="color:#fbbf24;"><i class="fa-solid fa-star"></i> 4.9</span>
                        </div>
                        <div><i class="fa-regular fa-calendar"></i> 30/3/2026</div>
                    </div>
                </div>
            </div>
            
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