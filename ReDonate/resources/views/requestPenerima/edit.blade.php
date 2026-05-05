<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Preferensi Permintaan - ReDonate</title>
    @vite(['resources/css/request.css'])
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h1>Ubah Preferensi Permintaan</h1>
            
            <div class="info-box">
                <p>Mengedit permintaan untuk barang:</p>
                <h3>{{ $requestItem->item->item_name ?? 'Barang tidak ditemukan' }}</h3>
            </div>

            <hr>

            <form action="{{ route('requests.update', $requestItem->id) }}" method="POST">
                @csrf
                @method('PUT') <div class="form-group">
                    <label for="title">Judul Permintaan</label>
                    <input type="text" id="title" value="{{ $requestItem->title }}" disabled class="input-disabled">
                    <small>*Judul permintaan tidak dapat diubah.</small>
                </div>

                <div class="form-group">
                    <label for="description">Preferensi Metode Pengambilan & Catatan</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="5" 
                        required 
                        placeholder="Contoh: Saya ingin mengambil barang sendiri pada hari Minggu pukul 10 pagi."
                    >{{ old('description', $requestItem->description) }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity">Jumlah yang Diminta</label>
                    <input type="number" id="quantity" value="{{ $requestItem->quantity }}" disabled class="input-disabled">
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                    <a href="{{ route('requests.index') }}" class="btn btn-secondary btn-block" style="text-align: center; display: block;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>