<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Daftar Reservasi</title>
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard Admin - Daftar Reservasi</h2>
        <!-- Tombol Navigasi ke Kelola Lapangan -->
        <a href="{{ route('admin.courts.index') }}" class="btn btn-outline-primary">Kelola Data Lapangan &rarr;</a>
    </div>

    <!-- Alert Notifikasi Berhasil -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Pemesan</th>
                            <th>No. Telp</th>
                            <th>Tanggal</th>
                            <th>Detail Lapangan</th>
                            <th>Total Harga</th>
                            <th>Status Saat Ini</th>
                            <th>Aksi Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop data reservasi dari BookingsController@index -->
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $booking->customer_name }}</td>
                                <td>{{ $booking->customer_phone }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                                
                                <!-- Relasi ke BookingDetail dan Court -->
                                <td>
                                    @foreach($booking->details as $detail)
                                        <div>
                                            <strong>{{ $detail->court->name ?? 'Lapangan Dihapus' }}</strong><br>
                                            <small class="text-muted">Jam: {{ $detail->start_time }} ({{ $detail->duration_hours }} Jam)</small>
                                        </div>
                                    @endforeach
                                </td>
                                
                                <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                
                                <!-- Badge Status Transaksi -->
                                <td>
                                    @if($booking->status == 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($booking->status == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Batal</span>
                                    @endif
                                </td>
                                
                                <!-- Form Update Status Pembayaran -->
                                <td>
                                    <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Lunas" {{ $booking->status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                            <option value="Batal" {{ $booking->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data reservasi.</td>
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