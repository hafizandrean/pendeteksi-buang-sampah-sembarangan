<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simbahrang - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            /* Warna Dasar Tampilan Glass-IT (Dark Mode Elegan) */
            --bg-color: #f2f7fb; /* Base terang slate 50 */
            --surface-color: rgba(255, 255, 255, 0.4); /* Kaca transparan terang */
            --surface-hover: rgba(255, 255, 255, 0.7);
            --border-color: rgba(226, 232, 240, 0.7); 
            
            /* Teks - Keep it dark for contrast against bright background */
            --text-primary: #1e293b; /* Slate 900 */
            --text-secondary: #475569; /* Slate 600 */
            
            /* Aksen-aksen neon ala IT */
            --accent-primary: #3b82f6; 
            --accent-primary-hover: #2563eb;
            --accent-button: #3b82f6;
            --accent-button-hover: #2563eb;
            --accent-danger: #ef4444; 
            --accent-success: #10b981; 
            --accent-warning: #f59e0b; 
            --accent-info: #0ea5e9;
            
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
            
            /* Manggil file gambar asli lu langsung */
            background-image: url('{{ asset("images/blurabsBG.png") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            
            color: var(--text-primary);
            min-height: 100vh;
            padding: 2rem;
            line-height: 1.5;
        }

        .container { max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem; }

        .card {
            background: var(--surface-color);
            /* INI KUNCI EFEK GLASS-NYA: BLUR YANG KUAT */
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        
        .card:hover { box-shadow: var(--shadow-md); background: var(--surface-hover); }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 1rem; }
        .brand-title { font-size: 2rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; margin-bottom: 0.2rem; }
        .header-desc { color: var(--text-secondary); font-size: 0.95rem; font-weight: 500; }
        .header-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem; }
        .system-health { display: flex; gap: 1rem; margin-bottom: 0.25rem; }
        .health-indicator { display: flex; align-items: center; gap: 0.3rem; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); }
        .health-dot { width: 8px; height: 8px; background-color: var(--accent-success); border-radius: 50%; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); }
        .time-live { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 500; }

        /* Buttons */
        .btn-group { display: flex; gap: 0.75rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.7rem 1.25rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.95rem; cursor: pointer; text-decoration: none; transition: all 0.2s ease; border: 1px solid transparent; }
        .btn-primary { background-color: var(--accent-button); color: #fff; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3); }
        .btn-primary:hover { background-color: var(--accent-button-hover); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5); }
        .btn-outline { background-color: rgba(255,255,255,0.5); color: var(--accent-primary); border-color: rgba(255,255,255,0.8); }
        .btn-outline:hover { background-color: rgba(255,255,255,0.8); }

        /* Stats Grid */
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; }
        .stat-card { display: flex; flex-direction: column; gap: 0.5rem; border-left: 4px solid var(--border-color); }
        .stat-card.warning { border-left-color: var(--accent-warning); }
        .stat-card.primary { border-left-color: var(--accent-primary); }
        .stat-card.success { border-left-color: var(--accent-success); }
        .stat-card.danger { border-left-color: var(--accent-danger); }
        .stat-title { color: var(--text-secondary); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { font-size: 2rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
        .stat-desc { font-size: 0.85rem; color: var(--text-secondary); }

        /* Layout Grid */
        .content-grid { display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem; }
        @media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; } }
        .section-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 0.75rem; }

        /* Benerin Input & Filter biar transparan pekat ala referensi b7d9f0.png */
        .filter-form select, .filter-form input { 
            background: rgba(255, 255, 255, 0.5) !important; 
            color: var(--text-primary) !important;
            border: 1px solid rgba(255,255,255,0.8) !important;
            backdrop-filter: blur(10px);
            border-radius: 8px;
            padding: 0.6rem 1rem;
            outline: none;
            font-family: inherit;
        }
        ::placeholder { color: #94a3b8 !important; opacity: 1; }
        .filter-form select option { background: #fff; color: #1e293b; }

        /* Table */
        .table-container { background: rgba(255,255,255,0.2) !important; border-radius: var(--radius-lg); overflow: hidden; margin-top: 1rem; border: 1px solid rgba(255,255,255,0.5);}
        table { width: 100%; border-collapse: separate; border-spacing: 0; text-align: left; }
        th { background: rgba(255, 255, 255, 0.3) !important; color: var(--text-secondary) !important; border-bottom: 1px solid rgba(255,255,255,0.4); padding: 1.25rem 1rem; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; }
        td { border-bottom: 1px solid rgba(255,255,255,0.3) !important; color: var(--text-primary); padding: 1.25rem 1rem; vertical-align: middle;}
        
        .thumbnail { width: 100px; height: 75px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: transform 0.3s ease; border: 1px solid rgba(255,255,255,0.5); }
        .thumbnail:hover { transform: scale(1.15); z-index: 10; position: relative; border-color: var(--accent-primary); box-shadow: var(--shadow-md); }

        /* Badges & Links ala IT (Neon glow tipis) */
        .badge { display: inline-flex; align-items: center; padding: 0.4rem 0.85rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; gap: 0.35rem; }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--accent-danger); border: 1px solid rgba(239, 68, 68, 0.2); }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--accent-warning); border: 1px solid rgba(245, 158, 11, 0.2); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--accent-success); border: 1px solid rgba(16, 185, 129, 0.2); }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-danger .badge-dot { background: var(--accent-danger); box-shadow: 0 0 4px var(--accent-danger);}
        .badge-warning .badge-dot { background: var(--accent-warning); box-shadow: 0 0 4px var(--accent-warning);}
        .badge-success .badge-dot { background: var(--accent-success); box-shadow: 0 0 4px var(--accent-success);}

        .action-link { color: var(--accent-primary); text-decoration: none; font-weight: 600; font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 8px; background: rgba(59, 130, 246, 0.1); transition: all 0.2s; border: 1px solid rgba(59, 130, 246, 0.2); }
        .action-link:hover { background: rgba(59, 130, 246, 0.2); }

        .btn-quick { background: rgba(255,255,255,0.5); border: 1px solid rgba(255,255,255,0.8); border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.35rem; color: var(--text-secondary); }
        .btn-quick:hover { background: rgba(255,255,255,0.9); color: var(--text-primary); }
        .btn-quick.valid:hover { background: rgba(16, 185, 129, 0.1); border-color: var(--accent-success); color: var(--accent-success); }
        .btn-quick.invalid:hover { background: rgba(239, 68, 68, 0.1); border-color: var(--accent-danger); color: var(--accent-danger); }

        /* Sidebar Elements */
        .sidebar-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .sidebar-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.02); border-radius: 8px; border: 1px solid var(--border-color); }
        .sidebar-item h4 { font-size: 0.95rem; font-weight: 600; color: white; }
        .sidebar-count { font-size: 1.25rem; font-weight: 700; color: var(--accent-primary); }
        
        .latest-card img, .latest-card video { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem; border: 1px solid rgba(0,0,0,0.05); }
        .latest-info p { margin-bottom: 0.4rem; font-size: 0.9rem; color: var(--text-secondary); }
        .latest-info strong { color: var(--text-primary); }

        /* Waktu Rawan Card Fix */
        .card[style*="background: rgba(14, 165, 233, 0.1)"] {
            border-left: 4px solid var(--accent-info) !important;
            background: rgba(255,255,255,0.4) !important;
        }

        /* Modal Image Viewer Glass */
        .modal-overlay { 
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(255, 255, 255, 0.3); backdrop-filter: blur(10px); z-index: 9999; 
            justify-content: center; align-items: center; padding: 2rem; 
            opacity: 0; transition: opacity 0.3s ease; 
        }
        .modal-overlay.active { opacity: 1; display: flex; }
        
        .modal-container { 
            background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.5); border-radius: var(--radius-lg); width: 100%; max-width: 900px; 
            max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2); 
            transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); 
            overflow: hidden; 
        }
        .modal-overlay.active .modal-container { transform: scale(1); }

        .modal-header { 
            padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; 
            border-bottom: 1px solid rgba(0,0,0,0.05); background: rgba(255,255,255,0.5); 
        }
        .modal-title { font-weight: 700; font-size: 1.2rem; color: var(--text-primary); margin: 0; }
        .modal-close { 
            background: transparent; border: none; color: var(--text-secondary); font-size: 1.5rem; 
            cursor: pointer; padding: 0.25rem; border-radius: 6px; transition: all 0.2s; 
        }
        .modal-close:hover { background: rgba(0,0,0,0.05); color: var(--accent-danger); }
        
        .modal-body { padding: 1.5rem; overflow-y: auto; display: flex; justify-content: center; align-items: center; background: rgba(255,255,255,0.2); min-height: 300px; }
        .modal-body img, .modal-body video { max-width: 100%; max-height: 70vh; border-radius: 8px; box-shadow: var(--shadow-md); border: 1px solid rgba(0,0,0,0.05); }

        /* Chart Container */
        .chart-container { position: relative; height: 250px; width: 100%; margin-top: 1rem; }

        /* Pagination Fix */
        .pagination { display: flex; list-style: none; gap: 0.5rem; justify-content: center; margin: 0; padding: 0; }
        .page-item .page-link { 
            display: flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; 
            border-radius: 8px; background: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.8); 
            color: var(--text-primary); text-decoration: none; font-size: 0.9rem; font-weight: 600; 
            transition: all 0.2s; 
        }
        .page-item.active .page-link { background: var(--accent-primary); border-color: var(--accent-primary); color: white; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3); }
        .page-item:not(.active):not(.disabled) .page-link:hover { background: rgba(255,255,255,0.9); }
        .page-item.disabled .page-link { opacity: 0.5; }

    </style>
</head>
<body>

    <div class="container">
        <header class="header card">
            <div>
                <h1 class="brand-title">Simbahrang</h1>
                <p class="header-desc">Sistem Monitoring Aktivitas Mencurigakan Sungai</p>
            </div>
            <div class="header-actions">
                <div class="system-health">
                    <span class="health-indicator"><div class="health-dot"></div> AI Aktif</span>
                    <span class="health-indicator"><div class="health-dot"></div> Telegram</span>
                    <span class="health-indicator"><div class="health-dot"></div> DB Normal</span>
                </div>
                <div class="time-live">
                    🕒 Update terakhir: <span id="current-time">{{ now()->isoFormat('D MMM YYYY • HH:mm') }} WIB</span>
                </div>
                <div class="btn-group">
                    <a href="{{ route('dashboard.export') }}" class="btn btn-outline">Export Data</a>
                    <a href="{{ route('dashboard.create') }}" class="btn btn-primary">Upload Bukti Baru</a>
                </div>
            </div>
        </header>

        @if (session('success'))
            <div class="card" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); padding: 1rem; border-radius: 8px; color: #6ee7b7; font-weight: 500; display:flex; gap: 0.5rem; align-items:center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <section>
            <div class="summary-grid">
                <div class="card stat-card warning">
                    <span class="stat-title">Total Laporan</span>
                    <span class="stat-value">{{ $totalDeteksi }}</span>
                    <span class="stat-desc">Data terpantau AI</span>
                </div>
                <div class="card stat-card primary">
                    <span class="stat-title">Titik Paling Rawan</span>
                    <span class="stat-value" style="font-size: 1.6rem; margin: 0.2rem 0;">{{ $lokasiRawan }}</span>
                    <span class="stat-desc">Sering terjadi indikasi</span>
                </div>
                <div class="card stat-card success">
                    <span class="stat-title">Validasi Selesai</span>
                    <span class="stat-value">{{ $valid }}</span>
                    <span class="stat-desc">Pelanggaran terkonfirmasi</span>
                </div>
                <div class="card stat-card danger">
                    <span class="stat-title">Dibatalkan (False)</span>
                    <span class="stat-value">{{ $diabaikan }}</span>
                    <span class="stat-desc">Bukan pelanggaran</span>
                </div>
            </div>
        </section>

        <div class="content-grid">
            <section class="card" style="display: flex; flex-direction: column;">
                <h2 class="section-title">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Data Pantauan AI
                </h2>
                
                <form class="filter-form" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;" method="GET" action="{{ route('dashboard.index') }}">
                    <select name="rentang_waktu" onchange="this.form.submit()">
                        <option value="">Semua Waktu</option>
                        <option value="Hari Ini" {{ request('rentang_waktu') == 'Hari Ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="Bulan Ini" {{ request('rentang_waktu') == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="Tahun Ini" {{ request('rentang_waktu') == 'Tahun Ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                    
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" title="Pilih Tanggal" onchange="this.form.submit()">
                    
                    <input type="text" name="lokasi" value="{{ request('lokasi') }}" placeholder="Cari Lokasi... (Tekan Enter)" style="flex-grow: 1; min-width: 150px;">
                    
                    <select name="status" onchange="this.form.submit()">
                        <option value="">Semua Status AI</option>
                        <option value="Tidak Terindikasi" {{ request('status') == 'Tidak Terindikasi' ? 'selected' : '' }}>Tidak Terindikasi</option>
                        <option value="Perlu Validasi" {{ request('status') == 'Perlu Validasi' ? 'selected' : '' }}>Perlu Validasi</option>
                        <option value="Indikasi Pelanggaran Tinggi" {{ request('status') == 'Indikasi Pelanggaran Tinggi' ? 'selected' : '' }}>Indikasi Pelanggaran Tinggi</option>
                    </select>

                    <select name="status_admin" onchange="this.form.submit()">
                        <option value="">Semua Status Admin</option>
                        <option value="Belum diverifikasi" {{ request('status_admin') == 'Belum diverifikasi' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Valid" {{ request('status_admin') == 'Valid' ? 'selected' : '' }}>Valid</option>
                        <option value="False detection" {{ request('status_admin') == 'False detection' ? 'selected' : '' }}>Diabaikan</option>
                    </select>

                    <select name="per_page" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Baris</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 Baris</option>
                    </select>
                    
                    <a href="{{ route('dashboard.index') }}" class="btn btn-outline" style="padding: 0.6rem 1rem;">Reset Filter</a>
                </form>

                <div class="table-container" style="flex-grow: 1;">
                    <table>
                        <thead>
                            <tr>
                                <th>Bukti</th>
                                <th>Info Waktu & Lokasi</th>
                                <th>Hasil Analisis AI</th>
                                <th>Status Admin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detections as $item)
                                <tr>
                                    <td>
                                        @if($item->gambar_bukti)
                                            @php 
                                                $ext = strtolower(pathinfo($item->gambar_bukti, PATHINFO_EXTENSION)); 
                                                $imagePath = str_starts_with($item->gambar_bukti, 'images/') 
                                                             ? asset($item->gambar_bukti) 
                                                             : asset('storage/'.$item->gambar_bukti);
                                            @endphp
                                            
                                            @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                                                <video src="{{ $imagePath }}" class="thumbnail" onclick="openModal('video', '{{ $imagePath }}')" muted></video>
                                            @else
                                                <img src="{{ $imagePath }}" class="thumbnail" onclick="openModal('img', '{{ $imagePath }}')" alt="Bukti">
                                            @endif
                                        @else
                                            <div class="thumbnail" style="background:#F1F5F9; display:flex; align-items:center; justify-content:center; color:#94A3B8; font-size:10px;">N/A</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight:600; color:var(--text-primary);">📍 {{ $item->lokasi }}</div>
                                        <div style="font-size:0.85rem; color:var(--text-secondary); margin-top: 4px;">🕒 {{ $item->waktu_kejadian ? $item->waktu_kejadian->format('d M Y H:i') : '-' }} WIB</div>
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 6px;">
                                            @if($item->status_indikasi == 'Indikasi Pelanggaran Tinggi')
                                                <span class="badge badge-danger"><div class="badge-dot"></div> {{ $item->status_indikasi }}</span>
                                            @elseif(in_array($item->status_indikasi, ['Perlu Validasi']))
                                                <span class="badge badge-warning"><div class="badge-dot"></div> {{ $item->status_indikasi }}</span>
                                            @else
                                                <span class="badge badge-success"><div class="badge-dot"></div> {{ $item->status_indikasi }}</span>
                                            @endif
                                        </div>
                                        @if($item->confidence_score)
                                            <div style="font-size:0.8rem; color:var(--text-secondary); font-weight:600;">Keyakinan AI: <span style="color:var(--accent-primary);">{{ $item->confidence_score * 100 }}%</span></div>
                                        @endif
                                    </td>
                                    <td>
                                        <div id="status-validasi-{{ $item->id }}">
                                            @if($item->status_validasi == 'Valid')
                                                <span class="badge badge-success"><div class="badge-dot"></div> Valid</span>
                                            @elseif($item->status_validasi == 'False detection')
                                                <span class="badge badge-danger"><div class="badge-dot"></div> Diabaikan</span>
                                            @else
                                                <span class="badge badge-warning" style="background: rgba(0,0,0,0.05); color: var(--text-secondary); border-color: transparent;"><div class="badge-dot" style="background: var(--text-secondary);"></div> Menunggu</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:flex; flex-direction:column; gap:0.5rem; align-items:flex-start;">
                                            @if($item->status_validasi != 'Valid' && $item->status_validasi != 'False detection')
                                            <div style="display:flex; gap:0.5rem;">
                                                <button onclick="quickValidate({{ $item->id }}, 'Valid')" class="btn-quick valid">✅ Valid</button>
                                                <button onclick="quickValidate({{ $item->id }}, 'False detection')" class="btn-quick invalid">❌ Abaikan</button>
                                            </div>
                                            @endif
                                            <a href="{{ route('dashboard.show', $item->id) }}" class="action-link" style="padding: 0.4rem 0.75rem; text-align:center;">Detail</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 4rem 1rem; color: var(--text-secondary); background: rgba(0,0,0,0.02);">
                                        <div style="font-size: 3rem; margin-bottom: 1rem;">🟢</div>
                                        <h3 style="color: var(--text-primary); font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem;">Belum ada aktivitas mencurigakan</h3>
                                        <p>Situasi saat ini terpantau aman dan terkendali.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 1.5rem;">
                    {{ $detections->links('pagination::bootstrap-4') }}
                </div>
            </section>

            <aside style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <div class="card">
                    <h2 class="section-title" style="font-size: 1rem;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        Grafik Deteksi AI
                    </h2>
                    <div class="chart-container">
                        <canvas id="statsChart"></canvas>
                    </div>
                </div>

                @php $latest = App\Models\Detection::latest('waktu_kejadian')->first(); @endphp
                @if($latest)
                <div class="card latest-card">
                    <h2 class="section-title" style="font-size: 1rem;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Tangkapan Terakhir
                    </h2>
                    @if($latest->gambar_bukti)
                        @php 
                            $ext = strtolower(pathinfo($latest->gambar_bukti, PATHINFO_EXTENSION)); 
                            $latestImagePath = str_starts_with($latest->gambar_bukti, 'images/') 
                                               ? asset($latest->gambar_bukti) 
                                               : asset('storage/'.$latest->gambar_bukti);
                        @endphp
                        @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                            <video src="{{ $latestImagePath }}" muted loop autoplay></video>
                        @else
                            <img src="{{ $latestImagePath }}" alt="Terakhir">
                        @endif
                    @endif
                    <div class="latest-info">
                        <p><strong>Waktu:</strong> {{ $latest->waktu_kejadian ? $latest->waktu_kejadian->format('H:i, d M Y') : '-' }}</p>
                        <p><strong>Lokasi:</strong> {{ $latest->lokasi }}</p>
                    </div>
                    <a href="{{ route('dashboard.show', $latest->id) }}" class="btn btn-outline" style="width:100%; text-align:center; margin-top: 0.5rem;">Validasi Sekarang</a>
                </div>
                @endif

                <div class="card" style="border-left: 4px solid var(--accent-info); background: rgba(14, 165, 233, 0.1);">
                    <h2 class="section-title" style="font-size: 1rem; color: var(--accent-info); border-color: rgba(14, 165, 233, 0.3);">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Waktu Paling Rawan
                    </h2>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <div style="font-size:2rem; font-weight:800; color:var(--text-primary);">{{ $jamTersibuk }}</div>
                            <div style="font-size:0.85rem; color:var(--text-secondary); font-weight:600;">Jam Puncak Aktivitas</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:1.5rem; font-weight:700; color:var(--accent-info);">{{ $totalJamTersibuk }}</div>
                            <div style="font-size:0.85rem; color:var(--text-secondary); font-weight:600;">Kejadian</div>
                        </div>
                    </div>
                </div>

            </aside>
        </div>
    </div>

    <div id="mediaModal" class="modal-overlay" onclick="closeModal()">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">Bukti Visual</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                </div>
        </div>
    </div>

    <script>
        // Modal Logic
        function openModal(type, src) {
            const modal = document.getElementById('mediaModal');
            const body = document.getElementById('modalBody');
            if (type === 'video') {
                body.innerHTML = `<video src="${src}" controls autoplay loop></video>`;
            } else {
                body.innerHTML = `<img src="${src}" alt="Bukti Full">`;
            }
            modal.classList.add('active');
        }

        function closeModal() {
            const modal = document.getElementById('mediaModal');
            modal.classList.remove('active');
            setTimeout(() => {
                document.getElementById('modalBody').innerHTML = '';
            }, 300);
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeModal();
        });

        // Update Live Time
        function updateTime() {
            const now = new Date();
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = now.getDate();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('current-time').textContent = `${day} ${months[now.getMonth()]} ${now.getFullYear()} • ${h}:${m} WIB`;
        }
        setInterval(updateTime, 60000);
        updateTime();

        // Quick Validation AJAX
        function quickValidate(id, status) {
            fetch(`/dashboard/detections/${id}/validation-ajax`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status_validasi: status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const container = document.getElementById(`status-validasi-${id}`);
                    if (status === 'Valid') {
                        container.innerHTML = `<span class="badge badge-success"><div class="badge-dot"></div> Valid</span>`;
                    } else {
                        container.innerHTML = `<span class="badge badge-danger"><div class="badge-dot"></div> Diabaikan</span>`;
                    }
                    const actionCell = container.closest('tr').querySelector('.btn-quick').parentElement;
                    if(actionCell) actionCell.style.display = 'none';
                }
            })
            .catch(err => console.error(err));
        }

        // Chart.js Setup
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('statsChart').getContext('2d');
            const data = {
                labels: ['Menunggu', 'Valid', 'Dibatalkan'],
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: [{{ $menungguValidasi }}, {{ $valid }}, {{ $diabaikan }}],
                    backgroundColor: ['rgba(245, 158, 11, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                    borderWidth: 0,
                    borderRadius: 6
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: { precision: 0, color: '#475569', font: { family: "'Plus Jakarta Sans', sans-serif" } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#475569', font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } }
                        }
                    }
                }
            };
            new Chart(ctx, config);
        });
    </script>
</body>
</html>