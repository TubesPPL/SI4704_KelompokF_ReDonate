<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Permintaan - ReDonate</title>
    @vite(['resources/css/request.css'])
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h1>Ajukan Permintaan Barang</h1>
            <p class="subtitle">Barang yang diminta: <strong>{{ $item->item_name }}</strong></p>

            <form action="{{ route('requests.store', $item->item_id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Judul Permintaan</label>
                    <input type="text" id="title" name="title" required placeholder="Misal: Butuh Pakaian Layak Pakai">
                </div>

                <div class="form-group">
                    <label for="quantity">Jumlah Barang</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                </div>

                <div class="form-group">
                    <label for="description">Catatan & Preferensi Pengambilan</label>
                    <textarea id="description" name="description" rows="4" required placeholder="Tuliskan alasan mengapa kamu butuh ini dan preferensi metode pengambilan (Misal: Ambil sendiri hari Sabtu)"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Kirim Permintaan</button>
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-block" style="text-align: center;">Kembali</a>
            </form>
        </div>
    </div>
</body>
</html>