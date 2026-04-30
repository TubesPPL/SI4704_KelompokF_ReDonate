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

<div class="dashboard-container">

    <!-- HEADER -->
    <div class="header">

        <div class="welcome-section">
            <img src="{{ $user->photo_url }}" class="avatar">

            <div class="welcome-content">
                <h1>Selamat Datang, {{ $user->name }}</h1>

                <span class="role-badge">
                    {{ $user->role_display }}
                </span>
            </div>
        </div>

        <!-- STATS -->
        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-number">
                    {{ $user->logs->count() ?? 0 }}
                </div>
                <div class="stat-label">Aktivitas</div>
            </div>

            <div class="stat-card">
                <div class="stat-number">
                    {{ $user->canDonate() ? '✓' : '✗' }}
                </div>
                <div class="stat-label">Donasi</div>
            </div>

            <div class="stat-card">
                <div class="stat-number">
                    {{ $user->canReceive() ? '✓' : '✗' }}
                </div>
                <div class="stat-label">Penerima</div>
            </div>

        </div>
    </div>

    <!-- ACTION CARDS -->
    <div class="action-grid">

        @if($user->canDonate())
        <div class="action-card">
            <h3>📦 Donasi Barang</h3>
            <p>Kelola dan buat donasi barang</p>

            <a href="javascript:void(0)">
                Mulai →
            </a>
        </div>
        @endif

        @if($user->canReceive())
        <div class="action-card">
            <h3>🔍 Cari Donasi</h3>
            <p>Lihat barang yang tersedia</p>

            <a href="javascript:void(0)">
                Lihat →
            </a>
        </div>
        @endif

        <!-- PROFILE -->
        <div class="action-card profile">
            <h3>👤 Kelola Profil</h3>
            <p>Edit data diri, alamat, dan akun</p>

            <a href="/profile">
                Buka Profil →
            </a>
        </div>

    </div>

    <!-- ACTIVITY -->
    <div class="activity-section">

        <h3>Aktivitas Terbaru</h3>

        <div class="activity-list">

            @forelse($user->logs as $log)

                <div class="activity-item">

                    <div class="activity-icon">
                        @switch($log->action)
                            @case('login_success') 🔑 @break
                            @case('register') ✨ @break
                            @case('logout') 👋 @break
                            @case('update_profile') ✏️ @break
                            @default 📊
                        @endswitch
                    </div>

                    <div>
                        <strong>{{ ucfirst(str_replace('_',' ',$log->action)) }}</strong>
                        <br>
                        <small>{{ $log->created_at->diffForHumans() }}</small>
                    </div>

                </div>

            @empty
                <p style="text-align:center;color:gray;">
                    Belum ada aktivitas
                </p>
            @endforelse

        </div>

    </div>

</div>

<!-- LOGOUT -->
<form method="POST" action="{{ route('logout') }}" id="logoutForm">
    @csrf
</form>

</body>
</html>