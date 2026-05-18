<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simbahrang - Detail Kejadian</title>
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
            --accent-success: #10B981;
            --accent-warning: #F97316; /* Orange Soft */
            --font-family: 'Plus Jakarta Sans', sans-serif;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 20px -4px rgba(0, 0, 0, 0.05), 0 4px 10px -4px rgba(0, 0, 0, 0.03);
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
            padding: 2rem;
            min-height: 100vh;
        }

        .container { width: 100%; max-width: 1000px; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }

        .header h1 { color: var(--accent-primary); margin: 0; font-size: 2rem; font-weight: 800; letter-spacing: -0.02em; }

        .btn-back {
            color: var(--text-secondary); text-decoration: none; font-weight: 600; font-size: 0.95rem;
            display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s;
            background: var(--surface-color); padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);
        }

        .btn-back:hover { color: var(--accent-primary); border-color: var(--accent-primary); transform: translateX(-2px); }

        .grid { display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem; }
        @media (max-width: 800px) { .grid { grid-template-columns: 1fr; } }

        .card { 
            background: var(--surface-color); border: 1px solid var(--border-color); padding: 1.5rem; 
            border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); 
        }

        .evidence-img { width: 100%; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 1.5rem; box-shadow: var(--shadow-md); }

        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1rem; }
        @media (max-width: 500px) { .detail-grid { grid-template-columns: 1fr; } }

        .detail-row { display: flex; flex-direction: column; }
        .detail-label { font-size: 0.8rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem; }
        .detail-value { font-size: 1.05rem; font-weight: 500; color: var(--text-primary); }

        .badge {
            display: inline-flex; align-items: center; padding: 0.4rem 0.85rem; border-radius: 99px; font-size: 0.85rem; font-weight: 700; gap: 0.4rem; letter-spacing: 0.02em;
        }
        .badge-dot { width: 8px; height: 8px; border-radius: 50%; }

        .badge-danger { background: rgba(244, 63, 94, 0.1); color: var(--accent-danger); border: 1px solid rgba(244, 63, 94, 0.2); }
        .badge-danger .badge-dot { background: var(--accent-danger); }
        .badge-warning { background: rgba(249, 115, 22, 0.1); color: var(--accent-warning); border: 1px solid rgba(249, 115, 22, 0.2); }
        .badge-warning .badge-dot { background: var(--accent-warning); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--accent-success); border: 1px solid rgba(16, 185, 129, 0.2); }
        .badge-success .badge-dot { background: var(--accent-success); }

        .form-group { margin-bottom: 1.25rem; }
        .form-group select, .form-group textarea {
            width: 100%; padding: 0.85rem; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit;
            background: var(--surface-color); color: var(--text-primary); font-size: 0.95rem; outline: none; transition: all 0.2s ease; box-shadow: var(--shadow-sm);
        }
        .form-group select:focus, .form-group textarea:focus { border-color: var(--accent-primary); box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1); }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; padding: 0.85rem 1.5rem; width: 100%;
            border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.2s ease;
        }

        .btn-primary { background: var(--accent-button); color: #fff; box-shadow: 0 4px 10px rgba(46, 125, 50, 0.2); }
        .btn-primary:hover { background: var(--accent-button-hover); transform: translateY(-1px); box-shadow: 0 6px 15px rgba(46, 125, 50, 0.3); }

        .alert-success {
            background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); padding: 1rem 1.5rem;
            border-radius: 8px; color: var(--accent-success); margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detail Indikasi</h1>
            <a href="{{ route('dashboard.index') }}" class="btn-back">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid">
            <!-- Left: Bukti & Info -->
            <div class="card">
                @if ($detection->gambar_bukti)
                    @php $ext = strtolower(pathinfo($detection->gambar_bukti, PATHINFO_EXTENSION)); @endphp
                    @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                        <video src="{{ asset('storage/'.$detection->gambar_bukti) }}" class="evidence-img" controls autoplay loop muted></video>
                    @else
                        <img src="{{ asset('storage/'.$detection->gambar_bukti) }}" class="evidence-img" alt="Bukti visual">
                    @endif
                @else
                    <div style="background:#F1F5F9; height:300px; display:flex; align-items:center; justify-content:center; border-radius:8px; margin-bottom:1.5rem; color:var(--text-secondary); border: 1px dashed var(--border-color); font-weight: 600;">
                        Tidak ada bukti visual
                    </div>
                @endif

                <div class="detail-grid">
                    <div class="detail-row">
                        <span class="detail-label">Status AI</span>
                        <div>
                            @if($detection->status_indikasi == 'Indikasi Pelanggaran Tinggi')
                                <span class="badge badge-danger"><div class="badge-dot"></div> {{ $detection->status_indikasi }}</span>
                            @elseif(in_array($detection->status_indikasi, ['Perlu Validasi']))
                                <span class="badge badge-warning"><div class="badge-dot"></div> {{ $detection->status_indikasi }}</span>
                            @else
                                <span class="badge badge-success"><div class="badge-dot"></div> {{ $detection->status_indikasi }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Keyakinan Sistem</span>
                        <span class="detail-value" style="color:var(--accent-primary); font-weight:700;">{{ $detection->confidence_score ? ($detection->confidence_score * 100).'%' : '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Waktu Kejadian</span>
                        <span class="detail-value">{{ optional($detection->waktu_kejadian)->format('d M Y, H:i') ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Lokasi Kejadian</span>
                        <span class="detail-value">{{ $detection->lokasi }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Identitas / Orang</span>
                        <span class="detail-value">{{ $detection->nama_pelaku ?? 'Belum diketahui' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Jenis Indikasi</span>
                        <span class="detail-value">{{ $detection->kategori_sampah ?? '-' }}</span>
                    </div>
                </div>

                <div class="detail-row" style="margin-top: 1rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                    <span class="detail-label">Keterangan Tambahan</span>
                    <span class="detail-value" style="font-size:0.95rem; line-height:1.6; color:var(--text-secondary);">{{ $detection->keterangan ?? 'Tidak ada catatan.' }}</span>
                </div>
            </div>

            <!-- Right: Admin Action -->
            <div>
                <div class="card" style="position: sticky; top: 2rem;">
                    <h2 style="font-size:1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.5rem; border-bottom:2px solid var(--border-color); padding-bottom:1rem; color: var(--accent-primary); font-weight: 800; letter-spacing: -0.01em;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Verifikasi Admin
                    </h2>
                    
                    <div class="detail-row" style="margin-bottom: 2rem;">
                        <span class="detail-label">Status Saat Ini</span>
                        <div>
                            @if($detection->status_validasi == 'Valid')
                                <span class="badge badge-success"><div class="badge-dot"></div> Terkonfirmasi Valid</span>
                            @elseif($detection->status_validasi == 'False detection')
                                <span class="badge badge-danger"><div class="badge-dot"></div> Diabaikan</span>
                            @else
                                <span class="badge badge-warning" style="background:var(--surface-hover); color:var(--text-secondary); border-color:transparent;"><div class="badge-dot" style="background:var(--text-secondary);"></div> Menunggu Verifikasi</span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('dashboard.validation', $detection->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label class="detail-label" for="status_validasi">Tetapkan Validasi</label>
                            <select name="status_validasi" id="status_validasi" required>
                                <option value="Belum diverifikasi" @selected($detection->status_validasi == 'Belum diverifikasi')>Belum diverifikasi</option>
                                <option value="Valid" @selected($detection->status_validasi == 'Valid')>Valid (Benar Pelanggaran)</option>
                                <option value="False detection" @selected($detection->status_validasi == 'False detection')>False (Bukan Pelanggaran)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="detail-label" for="tindak_lanjut">Tindak Lanjut & Catatan</label>
                            <textarea name="tindak_lanjut" id="tindak_lanjut" rows="4" placeholder="Misal: Sudah diteruskan ke ketua RT...">{{ $detection->tindak_lanjut }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 0.5rem;">Simpan Keputusan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
