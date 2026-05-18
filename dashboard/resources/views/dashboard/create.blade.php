<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simbahrang - Upload Bukti</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #F7F9F7;
            --surface-color: #FFFFFF;
            --surface-hover: #F1F5F9;
            --border-color: #E2E8F0;
            --text-primary: #1F2937;
            --text-secondary: #64748B;
            --accent-primary: #10B981; /* Emerald */
            --accent-primary-hover: #059669;
            --accent-button: #10B981;
            --accent-button-hover: #059669;
            --accent-danger: #F43F5E; /* Rose Soft */
            --font-family: 'Plus Jakarta Sans', sans-serif;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 20px -4px rgba(0, 0, 0, 0.05), 0 4px 10px -4px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 12px 25px -5px rgba(0, 0, 0, 0.1);
            --radius-md: 12px;
            --radius-lg: 20px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body { 
            font-family: var(--font-family); 
            background: var(--bg-color); 
            color: var(--text-primary); 
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            min-height: 100vh;
        }

        .container { width: 100%; max-width: 750px; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }

        .header h1 { color: var(--accent-primary); margin: 0; font-size: 2rem; font-weight: 800; letter-spacing: -0.02em; }

        .btn-back {
            color: var(--text-secondary); text-decoration: none; font-weight: 600; font-size: 0.95rem;
            display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s;
            background: var(--surface-color); padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);
        }

        .btn-back:hover { color: var(--accent-primary); border-color: var(--accent-primary); transform: translateX(-2px); }

        .card { 
            background: var(--surface-color); border: 1px solid var(--border-color); padding: 2.5rem; 
            border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); 
        }

        .field { margin-bottom: 1.5rem; }
        
        label {
            display: block; font-size: 0.85rem; color: var(--text-secondary); font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;
        }

        input, select, textarea {
            width: 100%; padding: 0.85rem; border: 1px solid var(--border-color); border-radius: 8px;
            font-family: inherit; background: var(--surface-color); color: var(--text-primary);
            font-size: 0.95rem; outline: none; transition: all 0.2s ease; box-shadow: var(--shadow-sm);
        }

        input[type="file"] {
            padding: 0.65rem; background: var(--surface-hover); cursor: pointer; border: 1px dashed var(--border-color);
        }
        
        input[type="file"]::file-selector-button {
            background: var(--surface-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem;
            border-radius: 6px; color: var(--text-primary); cursor: pointer; margin-right: 1rem;
            transition: all 0.2s; font-family: inherit; font-weight: 600;
        }
        input[type="file"]::file-selector-button:hover { background: var(--surface-hover); border-color: var(--accent-primary); }

        input:focus, select:focus, textarea:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1); }
        
        .time-group { display: flex; gap: 0.75rem; align-items: center; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; padding: 0.85rem 1.5rem;
            border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.2s ease;
        }

        .btn-primary { width: 100%; margin-top: 1rem; background: var(--accent-button); color: #fff; box-shadow: 0 4px 10px rgba(46, 125, 50, 0.2); font-size: 1.1rem; padding: 1rem; }
        .btn-primary:hover { background: var(--accent-button-hover); transform: translateY(-1px); box-shadow: 0 6px 15px rgba(46, 125, 50, 0.3); }

        .btn-outline { background: var(--surface-color); color: var(--accent-primary); border: 1px solid var(--border-color); font-size: 0.85rem; padding: 0.85rem 1rem; white-space: nowrap; box-shadow: var(--shadow-sm); }
        .btn-outline:hover { background: var(--surface-hover); border-color: var(--accent-primary); }

        .error-box { background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); padding: 1rem 1.5rem; border-radius: 8px; color: var(--accent-danger); margin-bottom: 2rem; font-weight: 500; }

        /* Loading Overlay */
        .loading-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px); z-index: 9999;
            flex-direction: column; justify-content: center; align-items: center;
        }

        .spinner {
            width: 60px; height: 60px; border: 5px solid rgba(46, 125, 50, 0.2);
            border-top-color: var(--accent-button); border-radius: 50%;
            animation: spin 1s linear infinite; margin-bottom: 1.5rem;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .loading-text { font-size: 1.5rem; font-weight: 800; color: var(--accent-primary); text-align: center; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
        .loading-subtext { font-size: 1rem; color: var(--text-secondary); margin-bottom: 1.5rem; text-align: center; min-height: 24px; font-weight: 500; }

        .loading-warning {
            font-size: 0.85rem; color: var(--accent-danger); background: rgba(220, 38, 38, 0.1); padding: 0.75rem 1.5rem;
            border-radius: 8px; font-weight: 700; text-align: center; border: 1px solid rgba(220, 38, 38, 0.2);
            display: flex; align-items: center; gap: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Upload Bukti Baru</h1>
            <a href="{{ route('dashboard.index') }}" class="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="card">
            @if ($errors->any())
                <div class="error-box">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem; font-weight:700;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Terdapat Kesalahan:
                    </div>
                    <ul style="margin:0; padding-left:1.5rem; font-size:0.9rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="uploadForm" action="{{ route('dashboard.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="field">
                    <label for="lokasi">Lokasi Pemantauan <span style="color:var(--accent-danger)">*</span></label>
                    <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi') }}" placeholder="Contoh: Sungai Ciliwung Jembatan Merah" required>
                </div>

                <div class="field">
                    <label for="nama_pelaku">Identitas / Ciri (Opsional)</label>
                    <input id="nama_pelaku" name="nama_pelaku" type="text" value="{{ old('nama_pelaku') }}" placeholder="Dapat dikosongkan jika anonim">
                </div>

                <div class="field">
                    <label for="waktu_kejadian">Waktu Rekaman <span style="color:var(--accent-danger)">*</span></label>
                    <div class="time-group">
                        <input id="waktu_kejadian" name="waktu_kejadian" type="datetime-local" value="{{ old('waktu_kejadian') }}" required>
                        <button type="button" class="btn btn-outline" onclick="setWaktuSekarang()">
                            Waktu Saat Ini
                        </button>
                    </div>
                </div>

                <div class="field">
                    <label for="jenis_bukti">Jenis Sumber <span style="color:var(--accent-danger)">*</span></label>
                    <select id="jenis_bukti" name="jenis_bukti" required>
                        <option value="Rekaman" @selected(old('jenis_bukti') === 'Rekaman')>Video (Rekomendasi untuk Analisis Durasi AI)</option>
                        <option value="Screenshot" @selected(old('jenis_bukti') === 'Screenshot')>Gambar Screenshot CCTV</option>
                        <option value="Foto" @selected(old('jenis_bukti') === 'Foto')>Foto Laporan Warga</option>
                    </select>
                </div>

                <div class="field">
                    <label for="gambar_bukti">File Media (MP4/JPG/PNG) <span style="color:var(--accent-danger)">*</span></label>
                    <input id="gambar_bukti" name="gambar_bukti" type="file" accept=".jpg,.jpeg,.png,.mp4,.mov,.avi,.mkv" required>
                </div>

                <div class="field">
                    <label for="keterangan">Keterangan Tambahan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan deskripsi atau catatan khusus untuk arsip...">{{ old('keterangan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="margin-right:8px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                    Mulai Analisis AI & Simpan
                </button>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text" id="loadingTitle">Engine Aktif</div>
        <div class="loading-subtext" id="loadingMessage">Mempersiapkan pipeline AI...</div>
        <div class="loading-warning">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            JANGAN TUTUP ATAU MUAT ULANG HALAMAN INI
        </div>
    </div>

    <script>
        function setWaktuSekarang() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            document.getElementById('waktu_kejadian').value = `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            if (this.checkValidity()) {
                document.getElementById('loadingOverlay').style.display = 'flex';
                
                const messages = [
                    "Mengunggah file ke server...",
                    "Mengekstrak frame visual...",
                    "AI sedang menganalisis aktivitas mencurigakan...",
                    "Memetakan pergerakan di area sungai...",
                    "Menghitung kalkulasi Confidence Score...",
                    "Menyimpan ke basis data Simbahrang...",
                    "Proses selesai, sedang mengalihkan..."
                ];
                
                let messageIndex = 0;
                const messageEl = document.getElementById('loadingMessage');
                const titleEl = document.getElementById('loadingTitle');
                
                titleEl.textContent = "AI Sedang Memproses";
                messageEl.textContent = messages[0];
                
                setInterval(() => {
                    messageIndex++;
                    if (messageIndex < messages.length) {
                        messageEl.textContent = messages[messageIndex];
                    }
                }, 2800);
            }
        });
    </script>
</body>
</html>
