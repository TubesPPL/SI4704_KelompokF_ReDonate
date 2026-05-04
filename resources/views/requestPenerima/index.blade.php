<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Permintaan Saya - ReDonate</title>
    @vite(['resources/css/request.css'])
</head>
<body>
    <div class="container">
        <h1>Daftar Permintaan Saya</h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="request-list">
            @forelse ($requests as $req)
                <div class="request-card">
                    <div class="card-header">
                        <h3>{{ $req->title }}</h3>
                        <span class="status badge-{{ $req->status }}">{{ ucfirst($req->status) }}</span>
                    </div>
                    <div class="card-body">
                        <p><strong>Barang:</strong> {{ $req->item->item_name ?? 'Barang Dihapus' }}</p>
                        <p><strong>Jumlah:</strong> {{ $req->quantity }}</p>
                        <p><strong>Catatan/Pickup:</strong> {{ $req->description }}</p>
                        <p class="date">Diajukan pada: {{ $req->created_at->format('d M Y') }}</p>
                    </div>
                    
                    <div class="card-actions">
                        @if($req->status === 'pending')
                            <a href="{{ route('requests.edit', $req->id) }}" class="btn btn-secondary">Ubah Preferensi</a>
                            
                            <form action="{{ route('requests.cancel', $req->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin membatalkan permintaan ini?')">Batalkan</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <p>Kamu belum pernah mengajukan permintaan barang.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>