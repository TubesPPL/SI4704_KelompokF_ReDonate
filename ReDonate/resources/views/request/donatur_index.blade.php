<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Masuk - ReDonate</title>
    @vite(['resources/css/donatur.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <div class="panel-container">
        
        <!-- PBI 17 & 20: Header Panel & Tombol Bersihkan Riwayat -->
        <div class="panel-header">
            <div class="panel-title">
                <a href="{{ route('dashboard') }}" style="color: #16a34a; text-decoration: none; margin-bottom: 0.5rem; display: inline-block;">&larr; Kembali ke Dashboard</a>
                <h1>Permintaan Masuk</h1>
                <p>Kelola permintaan barang dari penerima donasi Anda.</p>
            </div>

            <form action="{{ route('donatur.requests.clear') }}" method="POST" onsubmit="return confirm('Yakin ingin membersihkan riwayat yang ditolak?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-clear">
                    <i class="fa-solid fa-trash-can"></i> Bersihkan Riwayat
                </button>
            </form>
        </div>

        <!-- PBI 17: Menampilkan Daftar Request -->
        <div class="request-list">
            @forelse($requests as $req)
                <div class="request-card">
                    <div class="request-info">
                        <!-- Cek apakah ada foto barang, jika tidak pakai placeholder -->
                        <img src="{{ $req->item->image_full_url ?? asset('images/default-item.png') }}" alt="Item" class="item-thumbnail" onerror="this.src='https://via.placeholder.com/80'">
                        
                        <div class="request-details">
                            <h3>{{ $req->item->item_name ?? 'Barang Tidak Diketahui' }}</h3>
                            <div class="requester-name">
                                <img src="{{ $req->user->photo_url ?? asset('images/default-avatar.png') }}" class="requester-avatar" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($req->user->name ?? 'User') }}'">
                                Diminta oleh: <strong>{{ $req->user->name ?? 'Pengguna' }}</strong>
                            </div>
                            <div class="request-qty">Jumlah: {{ $req->quantity }} unit | Tanggal: {{ $req->created_at->format('d M Y') }}</div>
                        </div>
                    </div>

                    <!-- PBI 18 & 19: Tombol Aksi (Hanya muncul jika status pending) -->
                    <div class="request-actions">
                        @if($req->status === 'pending')
                            
                            <!-- Tombol Terima (Approve) -->
                            <form action="{{ route('donatur.requests.approve', $req->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action btn-approve" title="Setujui Permintaan"><i class="fa-solid fa-check"></i> Terima</button>
                            </form>

                            <!-- Tombol Tolak (Reject) -->
                            <form action="{{ route('donatur.requests.reject', $req->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action btn-reject" title="Tolak Permintaan"><i class="fa-solid fa-xmark"></i> Tolak</button>
                            </form>

                        @elseif($req->status === 'approved')
                            <span class="status-badge status-approved"><i class="fa-solid fa-check-circle"></i> Disetujui</span>
                        @elseif($req->status === 'rejected')
                            <span class="status-badge status-rejected"><i class="fa-solid fa-times-circle"></i> Ditolak</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                    <h2>Belum ada permintaan masuk</h2>
                    <p>Saat ini belum ada penerima yang meminta barang Anda.</p>
                </div>
            @endforelse
        </div>
        
    </div>

</body>
</html>