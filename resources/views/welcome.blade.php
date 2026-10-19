<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Lapangan Olahraga</title>
    <!-- Mengimpor stylesheet Bootstrap 5 dari CDN untuk styling tampilan -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <!-- Judul Utama Halaman Pelanggan -->
    <h2 class="text-center mb-4">Sistem Reservasi Lapangan Futsal & Olahraga</h2>

    <!-- NOTIFIKASI / ALERT SUKSES: Muncul jika proses reservasi berhasil disimpan -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- NOTIFIKASI / ALERT ERROR: Muncul jika jadwal bentrok atau validasi gagal -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- BAGIAN 1: DAFTAR LAPANGAN DARI DATABASE -->
    <h4 class="mb-3">Pilihan Lapangan</h4>
    <div class="row mb-5">
        <!-- Loop data $courts yang dikirim dari CourtsController@index -->
        @forelse($courts as $court)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <!-- Menampilkan foto lapangan dari folder storage jika ada -->
                    @if($court->image)
                        <img src="{{ asset('storage/' . $court->image) }}" class="card-img-top" alt="{{ $court->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <!-- Tampilan placeholder jika gambar tidak diunggah -->
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            Tidak Ada Gambar
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <!-- Nama Lapangan -->
                        <h5 class="card-title">{{ $court->name }}</h5>
                        <!-- Tipe Olahraga (Futsal, Badminton, Basket) -->
                        <span class="badge bg-primary mb-2">{{ $court->type }}</span>
                        <!-- Deskripsi singkat lapangan -->
                        <p class="card-text text-muted">{{ $court->description ?? 'Tidak ada deskripsi' }}</p>
                        <!-- Harga per jam di-format ke mata uang Rupiah -->
                        <h6 class="text-success fw-bold">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} / jam</h6>
                    </div>
                </div>
            </div>
        @empty
            <!-- Pesan jika belum ada data lapangan di database -->
            <div class="col-12">
                <div class="alert alert-warning text-center">Belum ada data lapangan tersedia.</div>
            </div>
        @endforelse
    </div>

    <!-- BAGIAN 2: FORM RESERVASI PELANGGAN -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Reservasi Lapangan</h5>
        </div>
        <div class="card-body">
            <!-- Form mengirimkan HTTP POST ke route 'booking.store' -->
            <form action="{{ route('booking.store') }}" method="POST">
                <!-- Direktif keamanan wajib dari Laravel untuk mencegah serangan CSRF -->
                @csrf
                
                <div class="row">
                    <!-- Input Nama Pelanggan -->
                    <div class="col-md-6 mb-3">
                        <label for="customer_name" class="form-label">Nama Pemesan</label>
                        <input type="text" name="customer_name" class="form-control" required placeholder="Masukkan nama Anda">
                    </div>
                    <!-- Input Nomor Telepon / WhatsApp -->
                    <div class="col-md-6 mb-3">
                        <label for="customer_phone" class="form-label">Nomor WhatsApp / Telepon</label>
                        <input type="text" name="customer_phone" class="form-control" required placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="row">
                    <!-- Dropdown Pilihan Lapangan -->
                    <div class="col-md-6 mb-3">
                        <label for="court_id" class="form-label">Pilih Lapangan</label>
                        <select name="court_id" class="form-select" required>
                            <option value="">-- Pilih Lapangan --</option>
                            <!-- Meloop data lapangan untuk dijadikan opsi dropdown -->
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}">
                                    {{ $court->name }} ({{ $court->type }}) - Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}/jam
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Input Tanggal Booking (Minimal hari ini) -->
                    <div class="col-md-6 mb-3">
                        <label for="booking_date" class="form-label">Tanggal Main</label>
                        <input type="date" name="booking_date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="row">
                    <!-- Input Jam Mulai -->
                    <div class="col-md-6 mb-3">
                        <label for="start_time" class="form-label">Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <!-- Input Durasi Main (Jam) -->
                    <div class="col-md-6 mb-3">
                        <label for="duration_hours" class="form-label">Durasi (Jam)</label>
                        <input type="number" name="duration_hours" class="form-control" min="1" value="1" required>
                    </div>
                </div>

                <!-- Tombol Submit Form -->
                <button type="submit" class="btn btn-success w-100">Kirim Reservasi</button>
            </form>
        </div>
    </div>
</div>

<!-- Mengimpor file Javascript Bootstrap untuk fungsionalitas interaktif (seperti menutup alert) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>