<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ReDonate</title>
    <meta name="description" content="Daftar akun ReDonate - Donasi barang layak pakai">
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>
<body>
<div class="container">
    <!-- Left Hero (Consistent with Login) -->
    <div class="left">
        <div class="logo">❤</div>
        <h1 class="title">ReDonate</h1>
        <p class="subtitle">
            Mulai Berbagi Kebaikan <br>
            <strong>Hari Ini</strong>
        </p>
        <div class="hero-box">
            Daftar sekarang dan jadilah bagian dari komunitas peduli yang
            berbagi barang layak pakai untuk mereka yang membutuhkan.
        </div>
    </div>

    <!-- Right Register Form -->
    <div class="right">
        <div class="auth-card register-card">
            <h2>Buat Akun Baru</h2>
            <p>Pilih role Anda dan lengkapi data untuk memulai</p>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> 
                    Periksa kembali data yang Anda masukkan
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Nama Lengkap -->
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="John Doe"
                        value="{{ old('name') }}"
                        required
                        aria-describedby="name-error"
                        class="{{ $errors->has('name') ? 'error' : '' }}"
                    >
                    @error('name')
                        <div id="name-error" class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        required
                        aria-describedby="email-error"
                        class="{{ $errors->has('email') ? 'error' : '' }}"
                    >
                    @error('email')
                        <div id="email-error" class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone & Address Row (PBI #3) -->
                <div class="field-row">
                    <div class="form-group">
                        <label for="phone">No. Telepon</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            placeholder="0812-3456-7890"
                            value="{{ old('phone') }}"
                            aria-describedby="phone-error"
                            class="{{ $errors->has('phone') ? 'error' : '' }}"
                        >
                        @error('phone')
                            <div id="phone-error" class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address" 
                            placeholder="Jl. Example No. 123"
                            value="{{ old('address') }}"
                            class="{{ $errors->has('address') ? 'error' : '' }}"
                        >
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Role Selection (PBI #1) -->
                <div class="form-group">
                    <label>Role Pengguna *</label>
                    <div class="role-group">
                        <label class="role-option">
                            <input type="radio" name="role" value="donatur" id="role-donatur" 
                                   {{ old('role') == 'donatur' ? 'checked' : '' }} required>
                            <span class="role-label">Donatur</span>
                            <span class="role-description">Donasi barang</span>
                        </label>

                        <label class="role-option">
                            <input type="radio" name="role" value="penerima" id="role-penerima"
                                   {{ old('role') == 'penerima' ? 'checked' : '' }}>
                            <span class="role-label">Penerima</span>
                            <span class="role-description">Menerima bantuan</span>
                        </label>

                        <label class="role-option">
                            <input type="radio" name="role" value="both" id="role-both"
                                   {{ old('role') != 'donatur' && old('role') != 'penerima' ? 'checked' : '' }}>
                            <span class="role-label">Keduanya</span>
                            <span class="role-description">Donasi & terima bantuan</span>
                        </label>
                    </div>
                    @error('role')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Minimal 8 karakter"
                        required
                        minlength="8"
                        aria-describedby="password-error"
                        class="{{ $errors->has('password') ? 'error' : '' }}"
                    >
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <small id="strengthText" class="text-muted">Password minimal 8 karakter</small>
                    </div>
                    @error('password')
                        <div id="password-error" class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password *</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="Ulangi password"
                        required
                        class="{{ $errors->has('password_confirmation') ? 'error' : '' }}"
                    >
                </div>

                <!-- Terms Agreement -->
                <div class="terms-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        Saya setuju dengan <a href="#" class="forgot-link">Syarat & Ketentuan</a> 
                        dan <a href="#" class="forgot-link">Kebijakan Privasi</a>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" id="registerBtn" class="btn-primary">
                    <span class="btn-text">Daftar Akun</span>
                    <span class="loading" id="loadingSpinner" style="display: none;"></span>
                </button>
            </form>

            <div class="link-row">
                Sudah punya akun? 
                <a href="{{ route('login') }}">Masuk Sekarang</a>
            </div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/your-kit.js" crossorigin="anonymous"></script>
@vite('resources/js/auth.js')
</body>
</html>