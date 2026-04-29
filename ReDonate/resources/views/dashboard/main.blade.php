<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#eaf5ee;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.card{
    background:#fff;
    padding:40px;
    border-radius:15px;
    width:400px;
    text-align:center;
    box-shadow:0 10px 20px rgba(0,0,0,0.1);
}

h2{
    margin-bottom:10px;
}

p{
    color:#555;
    margin-bottom:30px;
}

.btn{
    display:block;
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    text-decoration:none;
    color:#fff;
    font-weight:bold;
}

.btn-green{
    background:#16a34a;
}

.btn-blue{
    background:#2563eb;
}

.btn-gray{
    background:#555;
}
</style>
</head>

<body>

<div class="card">

    <h2>Selamat Datang 👋</h2>
    <p>Pilih aktivitas yang ingin kamu lakukan</p>

    <a href="/dashboard/donatur" class="btn btn-green">
        Donasi Barang
    </a>

    <a href="/dashboard/penerima" class="btn btn-blue">
        Cari Barang
    </a>

    <a href="/profile" class="btn btn-gray">
        Kelola Profil
    </a>

</div>

</body>
</html>