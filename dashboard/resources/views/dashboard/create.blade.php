<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; background: #f7f7f7; color: #222; }
        .card { background: #fff; border: 1px solid #ddd; padding: 16px; border-radius: 8px; max-width: 720px; }
        .field { margin-bottom: 12px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #bbb; border-radius: 6px; }
        button { padding: 10px 14px; border: 1px solid #999; border-radius: 6px; background: #fff; cursor: pointer; }
        .error { color: #b00020; margin-bottom: 8px; }
    </style>
</head>
<body>
    <h1>Upload Bukti Visual</h1>
    <p><a href="{{ route('dashboard.index') }}">Kembali ke Dashboard</a></p>

    <div class="card">
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('dashboard.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="field">
                <label for="lokasi">Lokasi</label>
                <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi') }}" required>
            </div>
            <div class="field">
                <label for="waktu_kejadian">Waktu Kejadian</label>
                <input id="waktu_kejadian" name="waktu_kejadian" type="datetime-local" value="{{ old('waktu_kejadian') }}" required>
            </div>
            <div class="field">
                <label for="jenis_bukti">Jenis Bukti</label>
                <select id="jenis_bukti" name="jenis_bukti" required>
                    <option value="Screenshot" @selected(old('jenis_bukti') === 'Screenshot')>Screenshot</option>
                    <option value="Rekaman" @selected(old('jenis_bukti') === 'Rekaman')>Rekaman</option>
                    <option value="Foto" @selected(old('jenis_bukti') === 'Foto')>Foto</option>
                </select>
            </div>
            <div class="field">
                <label for="status_indikasi">Status Indikasi (opsional, AI akan override)</label>
                <select id="status_indikasi" name="status_indikasi">
                    <option value="Normal" @selected(old('status_indikasi') === 'Normal')>Normal</option>
                    <option value="Mencurigakan" @selected(old('status_indikasi') === 'Mencurigakan')>Mencurigakan</option>
                </select>
            </div>
            <div class="field">
                <label for="gambar_bukti">File Bukti (Gambar/Video)</label>
                <input id="gambar_bukti" name="gambar_bukti" type="file" accept=".jpg,.jpeg,.png,.mp4,.mov,.avi,.mkv" required>
            </div>
            <div class="field">
                <label for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="4">{{ old('keterangan') }}</textarea>
            </div>
            <button type="submit">Simpan Data</button>
        </form>
    </div>
</body>
</html>
