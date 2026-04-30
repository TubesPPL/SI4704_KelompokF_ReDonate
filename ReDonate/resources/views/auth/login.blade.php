<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ReDonate</title>
    <meta name="description" content="Platform Donasi Barang Layak Pakai">
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
</head>
<body>
<div class="container">
    <!-- Left Hero -->
    <div class="left">
        <div class="logo">❤</div>
        <h1 class="title">ReDonate</h1>
        <p class="subtitle">
            Platform Donasi Barang Layak Pakai. <br>
            <strong>Bergabunglah dengan ribuan orang berbagi kebaikan</strong>
        </p>
        <div class="hero-box">
            Donasi barang berkualitas untuk mereka yang membutuhkan. 
            Satu klik, satu kebaikan.
        </div>
    </div>

    <!-- Right Form -->
    <div class="right">
        <div class="auth-card">
            <h2>Masuk ke Akun</h2>
            <p>Selamat datang kembali! Masukkan kredensial Anda</p>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf
                
                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="masukkan@email.com"
                        value="{{ old('email') }}"
                        required
                        aria-describedby="email-error"
                        class="{{ $errors->has('email') ? 'error' : '' }}"
                    >
                    @error('email')
                        <div id="email-error" class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                        aria-describedby="password-error"
                        class="{{ $errors->has('password') ? 'error' : '' }}"
                    >
                    @error('password')
                        <div id="password-error" class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember -->
                <div class="remember-row">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="loginBtn" class="btn-primary">
                    <span class="btn-text">Masuk ke Akun</span>
                    <span class="loading" id="loadingSpinner" style="display: none;"></span>
                </button>
            </form>

            <!-- Register Link -->
            <div class="link-row">
                Belum punya akun? 
                <a href="{{ route('register') }}">Buat Akun Baru</a>
            </div>
        </div>
    </div>
</div>

{{-- Font Awesome --}}
<script src="https://kit.fontawesome.com/your-kit.js" crossorigin="anonymous"></script>
</body>
</html>