<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kejadian</title>
</head>
<body>
    <h1>Detail Kejadian</h1>
    <p><a href="{{ route('dashboard.index') }}">Kembali ke Dashboard</a></p>

    <p><strong>Lokasi:</strong> {{ $detection->lokasi }}</p>
    <p><strong>Waktu:</strong> {{ optional($detection->waktu_kejadian)->format('d-m-Y H:i') ?? '-' }}</p>
    <p><strong>Status Indikasi:</strong> {{ $detection->status_indikasi }}</p>
    <p><strong>Status Validasi:</strong> {{ $detection->status_validasi }}</p>
    <p><strong>Keterangan:</strong> {{ $detection->keterangan ?? '-' }}</p>

    @if ($detection->gambar_bukti)
        <p><img src="{{ asset('storage/'.$detection->gambar_bukti) }}" alt="Bukti visual" style="max-width:400px"></p>
    @endif
</body>
</html>
