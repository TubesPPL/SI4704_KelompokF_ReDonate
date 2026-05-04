<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile - ReDonate</title>

    @vite(['resources/css/profile.css', 'resources/js/profile.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="profile-container">

    <!-- HEADER -->
    <div class="header">
        <div class="logo">ReDonate</div>

        <div class="header-actions">
            <div class="user-info">
                <img src="{{ asset('storage/'.$user->photo_url) }}" class="user-avatar-sm">
                <span>{{ $user->name }}</span>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main-content">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <h2 class="profile-name">{{ $user->name }}</h2>
            <p class="profile-email">{{ $user->email }}</p>

            <div class="status-badges">
                <span class="badge badge-role">{{ $user->role }}</span>
                <span class="badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $user->is_active ? 'AKTIF' : 'NONAKTIF' }}
                </span>
            </div>

        </div>

        <!-- CONTENT -->
        <div class="profile-main">

            <!-- EDIT PROFILE -->
            <div class="profile-card">

                <h2 class="profile-title">Edit Profil</h2>
                <p class="profile-subtitle">Perbarui data akun kamu</p>

                @if(session('success'))
                    <div class="alert-success">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- FOTO PROFILE -->
                    <div class="profile-avatar" style="margin-bottom:2rem;">
                        <img id="previewImage" src="{{ asset('storage/'.$user->photo_url) }}">

                        <label class="upload-photo">
                            <i class="fa-solid fa-camera"></i>
                            <input type="file" id="photo" name="photo" hidden>
                        </label>
                    </div>

                    <div class="form-grid">

                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">No HP</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-input">
                        </div>

                        <div class="form-group full-width">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-textarea">{{ $user->address }}</textarea>
                        </div>

                    </div>

                    <div class="action-section">
                        <button type="submit" class="btn btn-update">
                            💾 Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

            <!-- DANGER ZONE -->
            <div class="profile-card danger">

                <h2 class="profile-title" style="color:#dc2626;">Danger Zone</h2>
                <p class="profile-subtitle">Aksi permanen pada akun</p>

                <!-- DEACTIVATE -->
                <form method="POST" action="{{ route('profile.deactivate') }}">
                    @csrf

                    <input type="password" name="confirm_password" placeholder="Password" class="form-input" required>

                    <div class="action-section">
                        <button type="submit" class="btn btn-deactivate">
                            🚫 Nonaktifkan Akun
                        </button>
                    </div>
                </form>

                <!-- DELETE -->
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <input type="password" name="confirm_password" placeholder="Password" class="form-input" required>

                    <div class="action-section">
                        <button type="submit" class="btn btn-delete">
                            🗑 Hapus Akun
                        </button>
                    </div>
                </form>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <div class="action-section">
                        <button type="submit" class="btn btn-logout">
                            🚪 Logout
                        </button>
                    </div>
                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>