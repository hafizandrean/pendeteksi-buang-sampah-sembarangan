<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; background: #f7f7f7; color: #222; }
        .card { background: #fff; border: 1px solid #ddd; padding: 16px; border-radius: 8px; margin-bottom: 16px; }
        .row { display: flex; gap: 16px; flex-wrap: wrap; }
        .stat { flex: 1; min-width: 220px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #f0f0f0; }
        a.btn { display: inline-block; padding: 8px 12px; border: 1px solid #999; border-radius: 6px; text-decoration: none; color: #222; background: #fff; margin-right: 8px; }
        .ok { color: #0b7a0b; font-weight: bold; }
        .warn { color: #b26a00; font-weight: bold; }
    </style>
</head>
<body>
    @php
        $lastWeek = now()->subDays(7);
        $weekly = $detections->where('waktu_kejadian', '>=', $lastWeek);
        $weeklyCount = $weekly->count();
        $suspiciousCount = $weekly->where('status_indikasi', 'Mencurigakan')->count();
        $recommendation = 'Lanjutkan pemantauan rutin.';
        if ($suspiciousCount >= 10) {
            $recommendation = 'Prioritas tinggi: tingkatkan patroli dan pasang papan larangan tambahan.';
        } elseif ($suspiciousCount >= 3) {
            $recommendation = 'Perlu evaluasi: jadwalkan patroli tambahan pada jam rawan.';
        }
    @endphp

    <h1>Dashboard Monitoring Pembuangan Sampah</h1>

    <div class="card">
        <a class="btn" href="{{ route('dashboard.create') }}">Upload Bukti</a>
        <a class="btn" href="{{ route('dashboard.export') }}">Unduh CSV</a>
    </div>

    @if (session('success'))
        <div class="card ok">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="card stat">
            <strong>Total Kejadian (7 hari):</strong>
            <div>{{ $weeklyCount }}</div>
        </div>
        <div class="card stat">
            <strong>Indikasi Mencurigakan:</strong>
            <div class="{{ $suspiciousCount > 0 ? 'warn' : 'ok' }}">{{ $suspiciousCount }}</div>
        </div>
        <div class="card stat">
            <strong>Rekomendasi Tindakan:</strong>
            <div>{{ $recommendation }}</div>
        </div>
    </div>

    <div class="card">
        <h2>Laporan Mingguan</h2>
        <p>Ringkasan 7 hari terakhir untuk evaluasi lapangan dan edukasi.</p>
    </div>

    <h2>Data Kejadian</h2>
    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Status Indikasi</th>
                <th>Status Validasi</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detections as $item)
                <tr>
                    <td>{{ optional($item->waktu_kejadian)->format('d-m-Y H:i') ?? '-' }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td>{{ $item->status_indikasi }}</td>
                    <td>{{ $item->status_validasi }}</td>
                    <td><a href="{{ route('dashboard.show', $item->id) }}">Lihat</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada data deteksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
