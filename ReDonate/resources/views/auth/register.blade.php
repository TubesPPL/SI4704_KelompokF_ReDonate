<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - ReDonate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #cfe3d8;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .heart {
            font-size: 70px;
            color: green;
        }

        .brand {
            font-size: 32px;
            font-weight: bold;
            margin-top: 10px;
        }

        .desc {
            margin-top: 10px;
            color: #555;
        }

        .info-box {
            margin-top: 30px;
            background: #dbe9e1;
            padding: 20px;
            border-radius: 12px;
            width: 60%;
            text-align: center;
        }

        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #f5f5f5;
            padding: 30px;
            border-radius: 15px;
            width: 400px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: none;
            margin-top: 5px;
        }

        .role {
            margin-top: 10px;
        }

        .role label {
            display: block;
            background: #eaeaea;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: green;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .login {
            text-align: center;
            margin-top: 15px;
        }

        .login a {
            color: green;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- LEFT -->
    <div class="left">
        <div class="heart">❤</div>
        <div class="brand">ReDonate</div>
        <div class="desc">Mulai Berbagi Kebaikan Hari Ini</div>

        <div class="info-box">
            Daftar sekarang dan jadilah bagian dari komunitas peduli yang
            berbagi barang layak pakai untuk mereka yang membutuhkan.
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <div class="card">
            <h2>Register</h2>
            <div class="subtitle">Buat akun baru untuk memulai</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <label>Nama Lengkap</label>
                <input type="text" name="name" placeholder="John Doe">

                <label>Email</label>
                <input type="email" name="email" placeholder="nama@email.com">

                <label>Role Pengguna</label>
                <div class="role">
                    <label>
                        <input type="radio" name="role" value="donatur">
                        Donatur
                    </label>

                    <label>
                        <input type="radio" name="role" value="penerima">
                        Penerima
                    </label>

                    <label>
                        <input type="radio" name="role" value="both" checked>
                        Keduanya
                    </label>
                </div>

                <label>Password</label>
                <input type="password" name="password">

                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation">

                <button type="submit">Register</button>

                <div class="login">
                    Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                </div>

            </form>
        </div>
    </div>

</div>

</body>
</html>