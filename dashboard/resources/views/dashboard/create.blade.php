<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiCCTV - Upload Bukti</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #F4F8F2;
            --surface-color: #ffffff;
            --border-color: #e0e0e0;
            --text-primary: #263238;
            --text-secondary: #546e7a;
            --accent-primary: #2E7D32;
            --accent-button: #4CAF50;
            --accent-button-hover: #388e3c;
            --accent-danger: #e53935;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            padding: 2rem; 
            background: var(--bg-color); 
            color: var(--text-primary); 
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 800px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: var(--accent-primary);
            margin: 0;
            font-size: 1.75rem;
        }

        .btn-back {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-back:hover {
            color: var(--accent-primary);
            text-decoration: underline;
        }

        .card { 
            background: var(--surface-color); 
            border: 1px solid var(--border-color); 
            padding: 2rem; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); 
        }

        .field { margin-bottom: 1.25rem; }
        
        label { 
            display: block; 
            margin-bottom: 0.5rem; 
            font-weight: 600; 
            color: var(--text-secondary);
        }

        input, select, textarea { 
            width: 100%; 
            padding: 0.75rem; 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
        }

        .btn { 
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--accent-button);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--accent-button-hover);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--accent-primary);
            color: var(--accent-primary);
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .btn-outline:hover {
            background: rgba(46, 125, 50, 0.05);
        }

        .error-box { 
            background: rgba(229, 57, 53, 0.1); 
            color: var(--accent-danger); 
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid var(--accent-danger);
        }

        .time-group {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Upload Bukti Visual</h1>
            <a href="{{ route('dashboard.index') }}" class="btn-back">← Kembali ke Dashboard</a>
        </div>

        <div class="card">
            @if ($errors->any())
                <div class="error-box">
                    <ul style="margin:0; padding-left:1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dashboard.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="field">
                    <label for="lokasi">Lokasi Kejadian <span style="color:red">*</span></label>
                    <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi') }}" placeholder="Contoh: Jl. Sudirman No. 12" required>
                </div>

                <div class="field">
                    <label for="nama_pelaku">Nama Pelaku (Opsional)</label>
                    <input id="nama_pelaku" name="nama_pelaku" type="text" value="{{ old('nama_pelaku') }}" placeholder="Bisa dikosongkan jika tidak diketahui">
                </div>

                <div class="field">
                    <label for="waktu_kejadian">Waktu Kejadian <span style="color:red">*</span></label>
                    <div class="time-group">
                        <input id="waktu_kejadian" name="waktu_kejadian" type="datetime-local" value="{{ old('waktu_kejadian') }}" required>
                        <button type="button" class="btn btn-outline" onclick="setWaktuSekarang()">Gunakan Waktu Sekarang</button>
                    </div>
                </div>

                <div class="field">
                    <label for="jenis_bukti">Jenis Bukti <span style="color:red">*</span></label>
                    <select id="jenis_bukti" name="jenis_bukti" required>
                        <option value="Screenshot" @selected(old('jenis_bukti') === 'Screenshot')>Screenshot CCTV</option>
                        <option value="Rekaman" @selected(old('jenis_bukti') === 'Rekaman')>Rekaman CCTV</option>
                        <option value="Foto" @selected(old('jenis_bukti') === 'Foto')>Foto Warga</option>
                    </select>
                </div>

                <div class="field">
                    <label for="gambar_bukti">File Bukti (Gambar/Video) <span style="color:red">*</span></label>
                    <input id="gambar_bukti" name="gambar_bukti" type="file" accept=".jpg,.jpeg,.png,.mp4,.mov,.avi,.mkv" required>
                </div>

                <div class="field">
                    <label for="keterangan">Keterangan Tambahan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="4" placeholder="Tambahkan catatan jika diperlukan...">{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Analisis Menggunakan AI & Simpan</button>
            </form>
        </div>
    </div>

    <script>
        function setWaktuSekarang() {
            const now = new Date();
            // Format to YYYY-MM-DDThh:mm
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            const localDatetime = `${year}-${month}-${day}T${hours}:${minutes}`;
            document.getElementById('waktu_kejadian').value = localDatetime;
        }
    </script>
</body>
</html>
