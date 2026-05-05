<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Deteksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Dashboard Deteksi</h2>

    <!-- ✅ Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- 📊 Statistik -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Total Data</h5>
                <h3>{{ $detections->count() }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Mencurigakan</h5>
                <h3>{{ $detections->where('status_indikasi', 'Mencurigakan')->count() }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Sudah Divalidasi</h5>
                <h3>{{ $detections->where('status_validasi', '!=', 'Belum divalidasi')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- ➕ Tombol -->
    <a href="{{ route('dashboard.create') }}" class="btn btn-primary mb-3">
        + Tambah Data
    </a>

    <!-- 📋 Tabel -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Lokasi</th>
                <th>Waktu</th>
                <th>Status Indikasi</th>
                <th>Status Validasi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($detections as $d)
                <tr>
                    <td>{{ $d->lokasi }}</td>
                    <td>{{ $d->waktu_kejadian }}</td>
                    <td>{{ $d->status_indikasi }}</td>
                    <td>{{ $d->status_validasi }}</td>
                    <td>
                        <a href="{{ route('dashboard.show', $d->id) }}" class="btn btn-sm btn-info">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

</body>
</html>