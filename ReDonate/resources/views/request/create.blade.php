<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Barang - ReDonate</title>

    @vite(['resources/css/dashboard.css'])
</head>

<body>

<div class="dashboard-container">

    <div class="header">
        <h1>📥 Request Barang</h1>
        <p>Ajukan barang yang kamu butuhkan</p>
    </div>

    <div class="action-card" style="max-width:600px;margin:auto;">

        <!-- SUCCESS -->
        @if(session('success'))
            <div style="color:green;margin-bottom:10px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERROR -->
        @if($errors->any())
            <div style="color:red;margin-bottom:10px;">
                Periksa kembali input kamu
            </div>
        @endif

        <form method="POST" action="{{ route('request.store') }}">
            @csrf

            <!-- TITLE -->
            <div class="form-group">
                <label>Nama Barang *</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description">{{ old('description') }}</textarea>
            </div>

            <!-- CATEGORY -->
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}">
            </div>

            <!-- QUANTITY -->
            <div class="form-group">
                <label>Jumlah *</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                @error('quantity')
                    <small style="color:red;">{{ $message }}</small>
                @enderror
            </div>

            <!-- BUTTON -->
            <div style="margin-top:15px;">
                <button type="submit">Kirim Request</button>
                <a href="{{ route('dashboard') }}" style="margin-left:10px;">Kembali</a>
            </div>

        </form>

    </div>

</div>

</body>
</html>