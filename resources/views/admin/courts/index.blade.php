<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Master Data Lapangan</title>
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard Admin - Master Lapangan</h2>
        <!-- Tombol Navigasi Kembali ke Transaksi -->
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">&larr; Lihat Kelola Reservasi</a>
    </div>

    <!-- Alert Notifikasi Berhasil -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- FORM TAMBAH LAPANGAN BARU -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Tambah Lapangan Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nama Lapangan</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Lapangan A (Futsal)">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipe Olahraga</label>
                        <select name="type" class="form-select" required>
                            <option value="Futsal">Futsal</option>
                            <option value="Badminton">Badminton</option>
                            <option value="Basket">Basket</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga per Jam (Rp)</label>
                        <input type="number" name="price_per_hour" class="form-control" required placeholder="100000">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat fasilitas lapangan..."></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Upload Gambar Lapangan</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Lapangan</button>
            </form>
        </div>
    </div>

    <!-- TABEL DAFTAR LAPANGAN -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Daftar Master Lapangan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Nama Lapangan</th>
                            <th>Tipe Olahraga</th>
                            <th>Harga / Jam</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop data lapangan dari CourtsController@index -->
                        @forelse($courts as $court)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="width: 120px;">
                                    @if($court->image)
                                        <img src="{{ asset('storage/' . $court->image) }}" class="img-thumbnail" alt="{{ $court->name }}">
                                    @else
                                        <span class="badge bg-secondary">No Image</span>
                                    @endif
                                </td>
                                <td><strong>{{ $court->name }}</strong></td>
                                <td><span class="badge bg-info text-dark">{{ $court->type }}</span></td>
                                <td>Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}</td>
                                <td>{{ $court->description ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data lapangan. Sila tambahkan lewat form di atas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>