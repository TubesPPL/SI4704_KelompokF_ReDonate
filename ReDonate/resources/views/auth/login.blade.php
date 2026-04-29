<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login ReDonate</title>

<style>
body{
margin:0;
font-family:Arial;
background:#d8e9df;
display:flex;
height:100vh;
}

/* kiri */
.left{
flex:1;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
text-align:center;
}

.logo{
font-size:70px;
color:#16a34a;
}

.title{
font-size:30px;
font-weight:bold;
}

.subtitle{
margin-bottom:20px;
color:#555;
}

.box{
background:#cfe3d6;
padding:20px;
border-radius:12px;
width:300px;
}

/* kanan */
.right{
flex:1;
display:flex;
justify-content:center;
align-items:center;
}

.card{
background:#fff;
padding:40px;
border-radius:16px;
width:350px;
box-shadow:0 10px 20px rgba(0,0,0,0.1);
}

input{
width:100%;
padding:12px;
margin-bottom:15px;
border:none;
background:#f3f3f3;
border-radius:8px;
}

button{
width:100%;
padding:12px;
background:#16a34a;
color:#fff;
border:none;
border-radius:10px;
cursor:pointer;
}

button:hover{
background:#15803d;
}

.row{
display:flex;
justify-content:space-between;
font-size:13px;
margin-bottom:15px;
}
</style>
</head>

<body>

<div class="left">
<div class="logo">❤</div>
<div class="title">ReDonate</div>
<div class="subtitle">Platform Donasi Barang Layak Pakai</div>

<div class="box">
Bergabunglah dengan ribuan orang berbagi kebaikan melalui donasi barang berkualitas.
</div>
</div>

<div class="right">
<div class="card">

<h2>Login</h2>
<p>Masuk ke akun Anda untuk melanjutkan</p>

@if(session('success'))
    <div style="background:#d4edda;padding:10px;border-radius:5px;color:#155724;margin-bottom:10px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#f8d7da;padding:10px;border-radius:5px;color:#721c24;margin-bottom:10px;">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
@csrf

<input type="email" name="email" placeholder="Email">

<input type="password" name="password" placeholder="Password">

<div class="row">
<label><input type="checkbox"> Ingat saya</label>
<a href="#">Lupa password?</a>
</div>

<button type="submit">Login</button>

</form>

<p style="text-align:center;margin-top:15px;">
Belum punya akun? <a href="{{ route('register') }}">Register</a>
</p>

</div>
</div>

</body>
</html>