<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Kelola Profil</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root{
    --green:#16a34a;
    --green-dark:#15803d;
    --blue:#2563eb;
    --red:#dc2626;
    --gray:#555;
    --bg:#eaf5ee;
}

body{
    margin:0;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background:var(--bg);
}

.header{
    height:60px;
    background:white;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 30px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.logo{
    font-weight:bold;
    font-size:20px;
    color:var(--green);
}

.user-mini{
    font-size:14px;
    color:#333;
}

.container{
    display:flex;
    height:calc(100vh - 60px);
}

.sidebar{
    width:280px;
    background:#d4f3dc;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
}

.sidebar img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:15px;
}

.badge{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    color:#fff;
    margin-top:6px;
}

.role{ background:var(--green); }
.active{ background:var(--blue); }
.off{ background:var(--red); }

.content{
    flex:1;
    padding:40px;
}

.card{
    background:#fff;
    padding:30px;
    border-radius:16px;
    max-width:600px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

input, textarea{
    width:100%;
    padding:12px;
    margin-top:6px;
    margin-bottom:14px;
    border:none;
    background:#f4f4f4;
    border-radius:8px;
    transition:0.2s;
}

input:focus, textarea:focus{
    outline:none;
    background:#e8f5ec;
}

button{
    padding:12px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    transition:0.2s;
    font-weight:600;
}

.btn-green{
    background:var(--green);
    color:white;
}
.btn-green:hover{
    background:var(--green-dark);
}

.btn-red{
    background:var(--red);
    color:white;
}
.btn-red:hover{
    opacity:0.85;
}

.btn-gray{
    background:var(--gray);
    color:white;
}
.btn-gray:hover{
    opacity:0.85;
}

.row{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.alert{
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
    background:#d4edda;
    color:#155724;
}
</style>
</head>

<body>

<div class="header">
    <div class="logo">ReDonate</div>
    <div class="user-mini">
        {{ $user->name }} ({{ $user->role }})
    </div>
</div>

<div class="container">

    <div class="sidebar">

        @if($user->photo)
            <img src="{{ asset('profile/'.$user->photo) }}">
        @else
            <img src="https://via.placeholder.com/120">
        @endif

        <h3>{{ $user->name }}</h3>
        <p>{{ $user->email }}</p>

        <div class="badge role">
            {{ strtoupper($user->role) }}
        </div>

        @if($user->is_active)
            <div class="badge active">AKTIF</div>
        @else
            <div class="badge off">NONAKTIF</div>
        @endif

    </div>

    <div class="content">

        <div class="card">

            <h2>Kelola Profil</h2>

            @if(session('success'))
                <div class="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label>Nama</label>
                <input type="text" name="name" value="{{ $user->name }}">

                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}">

                <label>No HP</label>
                <input type="text" name="phone" value="{{ $user->phone }}">

                <label>Alamat</label>
                <textarea name="address">{{ $user->address }}</textarea>

                <label>Foto Profil</label>
                <input type="file" name="photo">

                <button class="btn-green">Update Profil</button>
            </form>

            <br>

            <div class="row">

                <form action="/profile/deactivate" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn-gray">Deaktivasi</button>
                </form>

                <form action="/profile/delete" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn-red">Hapus</button>
                </form>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button onclick="return confirm('Yakin logout?')" class="btn-red">
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>