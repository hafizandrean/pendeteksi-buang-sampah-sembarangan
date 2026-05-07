<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiCCTV - Detail Kejadian</title>
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
            --accent-warning: #FB8C00;
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
            max-width: 900px;
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

        .grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
        }

        .card { 
            background: var(--surface-color); 
            border: 1px solid var(--border-color); 
            padding: 1.5rem; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); 
        }

        .evidence-img {
            width: 100%;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-bottom: 1rem;
        }

        .detail-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .detail-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1.05rem;
            font-weight: 500;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-danger { background: rgba(229, 57, 53, 0.1); color: var(--accent-danger); }
        .badge-warning { background: rgba(251, 140, 0, 0.1); color: var(--accent-warning); }
        .badge-success { background: rgba(67, 160, 71, 0.1); color: var(--accent-primary); }

        .form-group { margin-bottom: 1rem; }
        .form-group select, .form-group textarea {
            width: 100%; 
            padding: 0.75rem; 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            font-family: inherit;
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
            width: 100%;
            transition: all 0.2s;
        }

        .btn-primary { background: var(--accent-button); color: white; }
        .btn-primary:hover { background: var(--accent-button-hover); }
        
        .alert-success {
            background: rgba(67, 160, 71, 0.1); 
            border: 1px solid var(--accent-success); 
            padding: 1rem; 
            border-radius: 8px; 
            color: var(--accent-success);
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detail Kejadian</h1>
            <a href="{{ route('dashboard.index') }}" class="btn-back">← Kembali ke Dashboard</a>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid">
            <!-- Left: Bukti & Info -->
            <div class="card">
                @if ($detection->gambar_bukti)
                    @php
                        $ext = strtolower(pathinfo($detection->gambar_bukti, PATHINFO_EXTENSION));
                    @endphp
                    @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                        <video src="{{ asset('storage/'.$detection->gambar_bukti) }}" class="evidence-img" controls></video>
                    @else
                        <img src="{{ asset('storage/'.$detection->gambar_bukti) }}" class="evidence-img" alt="Bukti visual">
                    @endif
                @else
                    <div style="background:#eee; height:300px; display:flex; align-items:center; justify-content:center; border-radius:8px; margin-bottom:1rem; color:#999">
                        Tidak ada bukti visual
                    </div>
                @endif

                <div class="detail-row">
                    <span class="detail-label">Status AI</span>
                    <div>
                        @if(in_array($detection->status_indikasi, ['Terindikasi membuang sampah', 'Mencurigakan']))
                            <span class="badge badge-danger">{{ $detection->status_indikasi }}</span>
                        @else
                            <span class="badge badge-success">{{ $detection->status_indikasi }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Nama Pelaku</span>
                    <span class="detail-value">{{ $detection->nama_pelaku ?? 'Tidak Diketahui' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Lokasi Kejadian</span>
                    <span class="detail-value">{{ $detection->lokasi }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Waktu Kejadian</span>
                    <span class="detail-value">{{ optional($detection->waktu_kejadian)->format('d F Y, H:i') ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Kategori Sampah (AI)</span>
                    <span class="detail-value">{{ $detection->kategori_sampah ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Tingkat Keyakinan (AI)</span>
                    <span class="detail-value">{{ $detection->confidence_score ? ($detection->confidence_score * 100).'%' : '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Keterangan Tambahan</span>
                    <span class="detail-value" style="font-size:0.95rem; line-height:1.5;">{{ $detection->keterangan ?? '-' }}</span>
                </div>
            </div>

            <!-- Right: Admin Action -->
            <div>
                <div class="card" style="position: sticky; top: 2rem;">
                    <h2 style="font-size:1.2rem; margin-top:0; margin-bottom:1.5rem; color:var(--accent-primary);">Verifikasi Admin</h2>
                    
                    <div class="detail-row" style="margin-bottom: 1.5rem;">
                        <span class="detail-label">Status Verifikasi Saat Ini</span>
                        <div>
                            @if($detection->status_validasi == 'Valid')
                                <span class="badge badge-success">Valid</span>
                            @elseif($detection->status_validasi == 'False detection')
                                <span class="badge badge-danger">False detection</span>
                            @else
                                <span class="badge badge-warning">Belum diverifikasi</span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('dashboard.validation', $detection->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label class="detail-label" for="status_validasi">Ubah Status</label>
                            <select name="status_validasi" id="status_validasi" required>
                                <option value="Belum diverifikasi" @selected($detection->status_validasi == 'Belum diverifikasi')>Belum diverifikasi</option>
                                <option value="Valid" @selected($detection->status_validasi == 'Valid')>Valid (Pelanggaran Terkonfirmasi)</option>
                                <option value="False detection" @selected($detection->status_validasi == 'False detection')>False detection (Salah Deteksi)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="detail-label" for="tindak_lanjut">Tindak Lanjut (Opsional)</label>
                            <textarea name="tindak_lanjut" id="tindak_lanjut" rows="4" placeholder="Misal: Diberikan peringatan RT">{{ $detection->tindak_lanjut }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
