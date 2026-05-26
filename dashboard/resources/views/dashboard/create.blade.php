<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simbahrang - Upload Bukti Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Samain persis kayak Dashboard Utama */
            --surface-color: rgba(255, 255, 255, 0.45); 
            --surface-hover: rgba(255, 255, 255, 0.7);
            --border-color: rgba(255, 255, 255, 0.7); 
            
            --text-primary: #1e293b; 
            --text-secondary: #475569; 
            
            --accent-primary: #3b82f6; 
            --accent-button: #3b82f6; /* Disamain jadi biru */
            --accent-button-hover: #2563eb;
            
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 20px 40px -5px rgba(0, 0, 0, 0.15);
            --radius-md: 14px;
            --radius-lg: 24px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-family);
            background-color: #f8fafc;
            
            /* Background Gambar ngikutin Dashboard */
            background-image: url('{{ asset("images/blurabsBG.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            
            color: var(--text-primary);
            min-height: 100vh;
            padding: 2rem;
            line-height: 1.5;
            display: flex;
            justify-content: center;
            align-items: center; /* Biar formnya di tengah layar */
        }

        .container { width: 100%; max-width: 750px; display: flex; flex-direction: column; gap: 1.5rem; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .header h2 { color: var(--accent-primary); font-weight: 800; font-size: 1.75rem; letter-spacing: -0.02em; }
        
        .btn-back {
            background: rgba(255,255,255,0.6); border: 1px solid var(--border-color);
            padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; color: var(--text-secondary);
            text-decoration: none; transition: all 0.2s; backdrop-filter: blur(10px);
            display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;
        }
        .btn-back:hover { background: rgba(255,255,255,0.9); color: var(--text-primary); box-shadow: var(--shadow-sm); }

        .card {
            background: var(--surface-color);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
        }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .form-group label span { color: #ef4444; } /* Bintang merah untuk required */
        
        input[type="text"], input[type="datetime-local"], select, textarea, input[type="file"] {
            width: 100%; background: rgba(255, 255, 255, 0.6); border: 1px solid var(--border-color);
            padding: 0.8rem 1rem; border-radius: 10px; font-family: inherit; font-size: 0.95rem;
            color: var(--text-primary); outline: none; transition: all 0.2s; backdrop-filter: blur(10px);
        }
        input:focus, select:focus, textarea:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); background: rgba(255,255,255,0.9); }
        
        /* Styling khusus buat input file */
        input[type="file"] { padding: 0.6rem; cursor: pointer; }
        input[type="file"]::file-selector-button {
            background: var(--accent-primary); color: white; border: none; padding: 0.5rem 1rem;
            border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s; margin-right: 1rem;
        }
        input[type="file"]::file-selector-button:hover { background: var(--accent-button-hover); }

        .input-group { display: flex; gap: 0.5rem; }
        .btn-current-time {
            background: rgba(255,255,255,0.6); border: 1px solid var(--border-color);
            color: var(--accent-primary); font-weight: 600; padding: 0 1rem; border-radius: 10px;
            cursor: pointer; transition: all 0.2s; backdrop-filter: blur(10px); white-space: nowrap;
        }
        .btn-current-time:hover { background: rgba(255,255,255,0.9); color: var(--accent-button-hover); }

        .btn-submit {
            width: 100%; background: var(--accent-button); color: white; border: none;
            padding: 1.1rem; border-radius: 12px; font-weight: 700; font-size: 1.05rem;
            cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 1rem;
        }
        .btn-submit:hover { background: var(--accent-button-hover); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5); }

        .error-message { color: #ef4444; font-size: 0.85rem; font-weight: 600; margin-top: 0.4rem; display: block; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>Upload Bukti Baru</h2>
            <a href="{{ route('dashboard.index') }}" class="btn-back">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="card">
            <form action="{{ route('dashboard.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Lokasi Pemantauan <span>*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Sungai Ciliwung Jembatan Merah" required>
                    @error('lokasi') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Identitas / Ciri (Opsional)</label>
                    <input type="text" name="nama_pelaku" value="{{ old('nama_pelaku') }}" placeholder="Dapat dikosongkan jika anonim">
                    @error('nama_pelaku') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Waktu Rekaman <span>*</span></label>
                    <div class="input-group">
                        <input type="datetime-local" id="waktu_kejadian" name="waktu_kejadian" value="{{ old('waktu_kejadian') }}" required>
                        <button type="button" class="btn-current-time" onclick="setWaktuSekarang()">Waktu Saat Ini</button>
                    </div>
                    @error('waktu_kejadian') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Jenis Sumber <span>*</span></label>
                    <select name="jenis_bukti" required>
                        <option value="Video" {{ old('jenis_bukti') == 'Video' ? 'selected' : '' }}>Video (Rekomendasi untuk Analisis Durasi AI)</option>
                        <option value="Foto" {{ old('jenis_bukti') == 'Foto' ? 'selected' : '' }}>Foto / Screenshot CCTV</option>
                    </select>
                    @error('jenis_bukti') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>File Media (MP4/JPG/PNG) <span>*</span></label>
                    <input type="file" name="gambar_bukti" accept="image/jpeg,image/png,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska" required>
                    @error('gambar_bukti') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Keterangan Tambahan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan deskripsi atau catatan khusus untuk arsip...">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Mulai Analisis AI & Simpan
                </button>
            </form>
        </div>
    </div>

    <script>
        function setWaktuSekarang() {
            const now = new Date();
            // Menyesuaikan format ke timezone lokal (WIB) untuk input datetime-local
            const offset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now - offset)).toISOString().slice(0,16);
            document.getElementById('waktu_kejadian').value = localISOTime;
        }
    </script>

</body>
</html>