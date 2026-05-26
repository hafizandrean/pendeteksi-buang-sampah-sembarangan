<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simbahrang - Detail Indikasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Samain persis kayak Dashboard Utama */
            --surface-color: rgba(255, 255, 255, 0.45); 
            --surface-hover: rgba(255, 255, 255, 0.7);
            --border-color: rgba(255, 255, 255, 0.7); 
            
            --text-primary: #1e293b; /* Dark Slate pekat untuk teks judul biar kontras */
            --text-secondary: #475569; 
            
            --accent-primary: #3b82f6; /* Royal Blue */
            --accent-button: #3b82f6; /* Warna tombol disamain sama tema Dashboard (Biru) */
            --accent-button-hover: #2563eb;
            
            --accent-danger: #ef4444; 
            --accent-success: #10b981; 
            --accent-warning: #f59e0b; 
            
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
        }

        .container { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        /* Warna judul diganti ke dark slate biar gak nyaru */
        .header h2 { color: var(--text-primary); font-weight: 800; font-size: 1.75rem; letter-spacing: -0.02em; }
        
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
            padding: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .content-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; }
        @media (max-width: 900px) { .content-grid { grid-template-columns: 1fr; } }

        /* Left Side: Detail Data */
        .image-container { width: 100%; border-radius: 12px; overflow: hidden; margin-bottom: 2rem; border: 1px solid rgba(0,0,0,0.05); box-shadow: var(--shadow-md); }
        .image-container img, .image-container video { width: 100%; height: auto; display: block; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem; }
        
        .info-item label { display: block; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; letter-spacing: 0.05em; }
        .info-item p { font-size: 1rem; font-weight: 600; color: var(--text-primary); }

        /* Right Side: Form Verifikasi */
        /* Warna sub-judul diganti biar kontras dan tegas */
        .form-section-title { font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem; }
        
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.5rem; }
        
        select, textarea {
            width: 100%; background: rgba(255, 255, 255, 0.6); border: 1px solid var(--border-color);
            padding: 0.8rem 1rem; border-radius: 10px; font-family: inherit; font-size: 0.95rem;
            color: var(--text-primary); outline: none; transition: all 0.2s; backdrop-filter: blur(10px);
        }
        select:focus, textarea:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); background: rgba(255,255,255,0.9); }
        
        .btn-submit {
            width: 100%; background: var(--accent-button); color: white; border: none;
            padding: 1rem; border-radius: 10px; font-weight: 700; font-size: 1rem;
            cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        .btn-submit:hover { background: var(--accent-button-hover); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5); }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 0.4rem 0.85rem; border-radius: 99px; font-size: 0.8rem; font-weight: 700; gap: 0.35rem; }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--accent-danger); border: 1px solid rgba(239, 68, 68, 0.2); }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--accent-warning); border: 1px solid rgba(245, 158, 11, 0.2); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--accent-success); border: 1px solid rgba(16, 185, 129, 0.2); }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-danger .badge-dot { background: var(--accent-danger); box-shadow: 0 0 4px var(--accent-danger);}
        .badge-warning .badge-dot { background: var(--accent-warning); box-shadow: 0 0 4px var(--accent-warning);}
        .badge-success .badge-dot { background: var(--accent-success); box-shadow: 0 0 4px var(--accent-success);}
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>Detail Indikasi</h2>
            <a href="{{ route('dashboard.index') }}" class="btn-back">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="card" style="padding: 1rem; margin-bottom: 1rem; background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #059669; font-weight: 600; display:flex; align-items:center; gap: 0.5rem;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="content-grid">
            <div class="card">
                <div class="image-container">
                    @php 
                        $ext = strtolower(pathinfo($detection->gambar_bukti, PATHINFO_EXTENSION)); 
                        $imagePath = str_starts_with($detection->gambar_bukti, 'images/') 
                                     ? asset($detection->gambar_bukti) 
                                     : asset('storage/'.$detection->gambar_bukti);
                    @endphp
                    @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                        <video src="{{ $imagePath }}" controls autoplay loop></video>
                    @else
                        <img src="{{ $imagePath }}" alt="Bukti Pelanggaran">
                    @endif
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Status AI</label>
                        @if($detection->status_indikasi == 'Indikasi Pelanggaran Tinggi')
                            <span class="badge badge-danger"><div class="badge-dot"></div> {{ $detection->status_indikasi }}</span>
                        @elseif($detection->status_indikasi == 'Indikasi Pelanggaran Sedang')
                            <span class="badge badge-warning"><div class="badge-dot"></div> {{ $detection->status_indikasi }}</span>
                        @else
                            <span class="badge badge-success" style="background: rgba(37, 99, 235, 0.1); color: var(--accent-primary); border-color: rgba(37, 99, 235, 0.2);"><div class="badge-dot" style="background: var(--accent-primary);"></div> {{ $detection->status_indikasi ?? 'Tidak Terindikasi' }}</span>
                        @endif
                    </div>

                    <div class="info-item">
                        <label>Keyakinan Sistem</label>
                        <p style="color: var(--accent-primary); font-size: 1.1rem; font-weight: 800;">
                            {{ $detection->confidence_score ? round($detection->confidence_score * 100) . '%' : 'N/A' }}
                        </p>
                    </div>

                    <div class="info-item">
                        <label>Waktu Kejadian</label>
                        <p>{{ $detection->waktu_kejadian ? $detection->waktu_kejadian->format('d M Y, H:i') : '-' }} WIB</p>
                    </div>

                    <div class="info-item">
                        <label>Lokasi Kejadian</label>
                        <p>{{ $detection->lokasi ?? '-' }}</p>
                    </div>

                    <div class="info-item">
                        <label>Identitas / Orang</label>
                        <p>{{ $detection->nama_pelaku ?? 'Belum diketahui' }}</p>
                    </div>

                    <div class="info-item">
                        <label>Jenis Indikasi</label>
                        <p style="text-transform: capitalize;">{{ $detection->kategori_sampah ?? $detection->jenis_bukti ?? 'Aktivitas Mencurigakan' }}</p>
                    </div>
                </div>

                <div class="info-item" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(0,0,0,0.05);">
                    <label>Keterangan Tambahan</label>
                    <p style="font-weight: 500; color: var(--text-secondary);">{{ $detection->keterangan ?? 'Tidak ada catatan.' }}</p>
                </div>
            </div>

            <div class="card" style="height: fit-content;">
                <h3 class="form-section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi Admin
                </h3>

                <div class="form-group">
                    <label>Status Saat Ini</label>
                    <div>
                        @if($detection->status_validasi == 'Valid')
                            <span class="badge badge-success"><div class="badge-dot"></div> Valid / Diteruskan</span>
                        @elseif($detection->status_validasi == 'False detection')
                            <span class="badge badge-danger"><div class="badge-dot"></div> Diabaikan</span>
                        @else
                            <span class="badge badge-warning" style="background: rgba(0,0,0,0.05); color: var(--text-secondary); border-color: transparent;"><div class="badge-dot" style="background: var(--text-secondary);"></div> Menunggu Verifikasi</span>
                        @endif
                    </div>
                </div>

                <form action="{{ route('dashboard.updateValidation', $detection->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-group">
                        <label>Tetapkan Validasi</label>
                        <select name="status_validasi">
                            <option value="Belum diverifikasi" {{ $detection->status_validasi == 'Belum diverifikasi' ? 'selected' : '' }}>Belum diverifikasi</option>
                            <option value="Valid" {{ $detection->status_validasi == 'Valid' ? 'selected' : '' }}>Valid (Pelanggaran Asli)</option>
                            <option value="False detection" {{ $detection->status_validasi == 'False detection' ? 'selected' : '' }}>Abaikan (Bukan Pelanggaran)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tindak Lanjut & Catatan</label>
                        <textarea name="keterangan" rows="4" placeholder="Misal: Sudah diteruskan ke ketua RT...">{{ $detection->keterangan }}</textarea>
                    </div>

                    <button type="submit" class="btn-submit">Simpan Keputusan</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>