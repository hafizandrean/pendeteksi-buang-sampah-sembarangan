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
            --bg-color: #f2f7fb; 
            --surface-color: rgba(255, 255, 255, 0.45); 
            --surface-hover: rgba(255, 255, 255, 0.65);
            --border-color: rgba(255, 255, 255, 0.7); 
            --text-primary: #1e293b; 
            --text-secondary: #475569; 
            --accent-primary: #3b82f6; 
            --accent-button: #3b82f6;
            --accent-button-hover: #2563eb;
            --accent-danger: #ef4444; 
            --accent-success: #10b981; 
            --accent-warning: #f59e0b; 
            --font-family: 'Plus Jakarta Sans', sans-serif;
            --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px -3px rgba(0,0,0,0.1);
            --shadow-lg: 0 20px 40px -5px rgba(0,0,0,0.15);
            --radius-lg: 24px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-family);
            background-image: url('{{ asset("images/blurabsBG.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--text-primary);
            padding: 2rem;
            line-height: 1.5;
        }

        .container { max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem; }

        .card { background: var(--surface-color); backdrop-filter: blur(25px); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-sm); transition: all 0.2s ease; }
        .card:hover { box-shadow: var(--shadow-md); background: var(--surface-hover); }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
        .brand-title { font-size: 2rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.2rem; }
        
        .system-health { display: flex; gap: 1rem; margin-bottom: 0.35rem; justify-content: flex-end; }
        .health-indicator { display: flex; align-items: center; gap: 0.3rem; font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); }
        .health-dot { width: 8px; height: 8px; background-color: var(--accent-success); border-radius: 50%; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); }
        .time-live { display: flex; justify-content: flex-end; align-items: center; gap: 0.4rem; font-size: 0.8rem; color: var(--text-secondary); font-weight: 600; margin-bottom: 1rem; }

        .btn-group { display: flex; gap: 0.75rem; justify-content: flex-end; }
        .btn { display: inline-flex; align-items: center; padding: 0.6rem 1.2rem; border-radius: 12px; font-weight: 700; font-size: 0.9rem; cursor: pointer; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; gap: 6px; }
        .btn-primary { background: var(--accent-button); color: #fff; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        .btn-primary:hover { background: var(--accent-button-hover); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(59,130,246,0.4); }
        .btn-outline { background: rgba(255,255,255,0.5); color: var(--text-primary); border-color: rgba(255,255,255,0.8); }
        .btn-outline:hover { background: white; }

        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; }
        .stat-card { border-left: 4px solid var(--border-color); display: flex; flex-direction: column; gap: 0.3rem; }
        .stat-card.warning { border-left-color: var(--accent-warning); }
        .stat-card.primary { border-left-color: var(--accent-primary); }
        .stat-card.success { border-left-color: var(--accent-success); }
        .stat-card.danger { border-left-color: var(--accent-danger); }
        .stat-title { color: var(--text-secondary); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { font-size: 1.8rem; font-weight: 800; line-height: 1.2; }

        .content-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; }
        @media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; } }
        .section-title { font-size: 1.15rem; font-weight: 800; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }

        /* Filter Form 1 Baris (Ramping) */
        .filter-wrapper { background: rgba(255,255,255,0.3); padding: 0.8rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.5); }
        .filter-form { display: flex; gap: 0.5rem; flex-wrap: nowrap; align-items: center; }
        .filter-form select, .filter-form input, .btn-reset { 
            background: rgba(255,255,255,0.7); color: var(--text-primary); border: 1px solid rgba(255,255,255,0.9); 
            border-radius: 8px; padding: 0.5rem 0.6rem; outline: none; font-family: inherit; font-size: 0.8rem; font-weight: 600; 
        }
        .filter-form .search-input { flex: 1; min-width: 120px; }
        .btn-reset { text-decoration: none; display: inline-flex; align-items: center; cursor: pointer; transition: all 0.2s; background: white;}
        .btn-reset:hover { background: #f8fafc; }

        /* Table */
        .table-container { background: rgba(255,255,255,0.2); border-radius: var(--radius-lg); overflow: hidden; border: 1px solid rgba(255,255,255,0.5);}
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { background: rgba(255,255,255,0.3); color: var(--text-secondary); border-bottom: 1px solid rgba(255,255,255,0.4); padding: 1.2rem 1rem; font-size: 0.75rem; text-transform: uppercase; font-weight: 800; text-align: center; }
        td { border-bottom: 1px solid rgba(255,255,255,0.2); padding: 1rem; vertical-align: middle; }
        
        .td-center { text-align: center; }
        .thumbnail { width: 90px; height: 65px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: transform 0.3s ease; border: 2px solid white; display: inline-block; }
        .thumbnail:hover { transform: scale(1.15); z-index: 10; position: relative; box-shadow: var(--shadow-md); }

        .badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.35rem 0.7rem; border-radius: 99px; font-size: 0.7rem; font-weight: 800; gap: 5px; text-transform: uppercase; }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: #b91c1c; }
        .badge-warning { background: rgba(245, 158, 11, 0.15); color: #b45309; }
        .badge-success { background: rgba(16, 185, 129, 0.15); color: #047857; }
        .badge-info { background: rgba(59, 130, 246, 0.15); color: #1d4ed8; }
        .badge-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor;}

        /* Aksi Buttons */
        .aksi-wrapper { display: flex; flex-direction: column; gap: 0.4rem; width: 100%; min-width: 130px; }
        .btn-quick-group { display: flex; gap: 0.4rem; width: 100%; }
        .btn-quick { flex: 1; background: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.8); border-radius: 6px; padding: 0.4rem 0; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; color: var(--text-secondary); text-align: center;}
        .btn-quick:hover { background: white; color: var(--text-primary); }
        .btn-quick.valid:hover { background: rgba(16, 185, 129, 0.1); border-color: var(--accent-success); color: var(--accent-success); }
        .btn-quick.invalid:hover { background: rgba(239, 68, 68, 0.1); border-color: var(--accent-danger); color: var(--accent-danger); }
        
        .action-link { display: block; width: 100%; color: var(--accent-primary); text-decoration: none; font-weight: 800; font-size: 0.75rem; padding: 0.4rem 0; border-radius: 6px; background: rgba(59, 130, 246, 0.1); transition: all 0.2s; border: 1px solid rgba(59, 130, 246, 0.2); text-align: center; }
        .action-link:hover { background: rgba(59, 130, 246, 0.2); }

        /* Sidebar Elements */
        .latest-card-img { width: 100%; max-width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 0.8rem; border: 2px solid white; display: block; }
        .sidebar-rawan { background: rgba(255,255,255,0.45); border: 1px solid rgba(255,255,255,0.7); }
        .chart-container { position: relative; height: 230px; width: 100%; margin-top: 1rem; }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.3); backdrop-filter: blur(10px); z-index: 9999; justify-content: center; align-items: center; padding: 2rem; opacity: 0; transition: opacity 0.3s; }
        .modal-overlay.active { opacity: 1; display: flex; }
        .modal-container { background: rgba(255,255,255,0.9); border-radius: var(--radius-lg); width: 100%; max-width: 800px; padding: 8px; box-shadow: var(--shadow-lg); }
        .modal-body img, .modal-body video { width: 100%; max-height: 80vh; border-radius: 18px; display: block; }

        /* Pagination */
        .pagination { display: flex; list-style: none; gap: 0.4rem; justify-content: center; margin: 0; padding: 0; }
        .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.8); color: var(--text-primary); text-decoration: none; font-size: 0.85rem; font-weight: 700; transition: all 0.2s; }
        .page-item.active .page-link { background: var(--accent-primary); border-color: var(--accent-primary); color: white; }
        .page-item:not(.active):not(.disabled) .page-link:hover { background: white; }
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
            <div>
                <div class="system-health">
                    <span class="health-indicator"><div class="health-dot"></div> AI Aktif</span>
                    <span class="health-indicator"><div class="health-dot"></div> Telegram</span>
                    <span class="health-indicator"><div class="health-dot"></div> DB Normal</span>
                </div>
                
                @php $lastUpdate = \App\Models\Detection::max('updated_at'); @endphp
                <div class="time-live">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Update terakhir: {{ $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->isoFormat('D MMM YYYY • HH:mm') : '-' }} WIB
                </div>

                <div class="btn-group">
                    <a href="{{ route('dashboard.export') }}" class="btn btn-outline">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Export
                    </a>
                    <a href="{{ route('dashboard.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg> Upload Bukti
                    </a>
                </div>
            </div>
        </header>

        <section class="summary-grid">
            <div class="card stat-card warning">
                <span class="stat-title">Total Laporan</span>
                <span class="stat-value">{{ $totalDeteksi }}</span>
            </div>
            <div class="card stat-card primary">
                <span class="stat-title">Titik Paling Rawan</span>
                <span class="stat-value" style="font-size: 1.4rem;">{{ $lokasiRawan }}</span>
            </div>
            <div class="card stat-card success">
                <span class="stat-title">Validasi Selesai</span>
                <span class="stat-value">{{ $valid }}</span>
            </div>
            <div class="card stat-card danger">
                <span class="stat-title">Dibatalkan</span>
                <span class="stat-value">{{ $diabaikan }}</span>
            </div>
        </section>

        <div class="content-grid">
            <section class="card" style="display: flex; flex-direction: column;">
                <h2 class="section-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Data Pantauan AI
                </h2>
                
                <div class="filter-wrapper">
                    <form class="filter-form" id="filterForm" method="GET" action="{{ route('dashboard.index') }}">
                        <select name="rentang_waktu" id="rentang_waktu" onchange="clearTanggalAndSubmit()">
                            <option value="">Semua Waktu</option>
                            <option value="Hari Ini" {{ request('rentang_waktu') == 'Hari Ini' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="Bulan Ini" {{ request('rentang_waktu') == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="Tahun Ini" {{ request('rentang_waktu') == 'Tahun Ini' ? 'selected' : '' }}>Tahun Ini</option>
                        </select>
                        <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}" onchange="clearRentangAndSubmit()">
                        <select name="status" onchange="this.form.submit()">
                            <option value="">Semua Indikasi</option>
                            <option value="Indikasi Pelanggaran Rendah" {{ request('status') == 'Indikasi Pelanggaran Rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="Indikasi Pelanggaran Sedang" {{ request('status') == 'Indikasi Pelanggaran Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Indikasi Pelanggaran Tinggi" {{ request('status') == 'Indikasi Pelanggaran Tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                        <select name="status_admin" onchange="this.form.submit()">
                            <option value="">Status Admin</option>
                            <option value="Belum diverifikasi" {{ request('status_admin') == 'Belum diverifikasi' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Valid" {{ request('status_admin') == 'Valid' ? 'selected' : '' }}>Valid</option>
                            <option value="False detection" {{ request('status_admin') == 'False detection' ? 'selected' : '' }}>Diabaikan</option>
                        </select>
                        <input type="text" name="lokasi" class="search-input" value="{{ request('lokasi') }}" placeholder="Cari Lokasi... (Enter)">
                        <a href="{{ route('dashboard.index') }}" class="btn-reset">Reset</a>
                    </form>
                </div>

                <div class="table-container" style="flex-grow: 1;">
                    <table>
                        <thead>
                            <tr>
                                <th>Bukti</th>
                                <th>Lokasi & Waktu</th>
                                <th>Hasil AI</th>
                                <th>Status Admin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detections as $item)
                                <tr>
                                    <td class="td-center">
                                        @if($item->gambar_bukti)
                                            @php 
                                                $ext = strtolower(pathinfo($item->gambar_bukti, PATHINFO_EXTENSION)); 
                                                $imagePath = str_starts_with($item->gambar_bukti, 'images/') ? asset($item->gambar_bukti) : asset('storage/'.$item->gambar_bukti);
                                            @endphp
                                            @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                                                <video src="{{ $imagePath }}" class="thumbnail" onclick="openModal('video', '{{ $imagePath }}')" muted></video>
                                            @else
                                                <img src="{{ $imagePath }}" class="thumbnail" onclick="openModal('img', '{{ $imagePath }}')">
                                            @endif
                                        @else
                                            <div class="thumbnail" style="background:rgba(255,255,255,0.5); display:flex; align-items:center; justify-content:center; color:#94A3B8; font-size:10px;">N/A</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex; flex-direction:column; align-items: flex-start; margin: 0 auto; width: fit-content;">
                                            <div style="font-weight:800; display:flex; align-items:center; gap:5px;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 
                                                {{ $item->lokasi }}
                                            </div>
                                            <div style="font-size:0.8rem; color:var(--text-secondary); margin-top: 4px; font-weight: 600; display:flex; align-items:center; gap:5px;">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> 
                                                {{ $item->waktu_kejadian ? $item->waktu_kejadian->format('d M Y, H:i') : '-' }} WIB
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-center">
                                        <div style="margin-bottom: 6px;">
                                            @if($item->status_indikasi == 'Indikasi Pelanggaran Tinggi')
                                                <span class="badge badge-danger"><div class="badge-dot"></div> TINGGI</span>
                                            @elseif($item->status_indikasi == 'Indikasi Pelanggaran Sedang')
                                                <span class="badge badge-warning"><div class="badge-dot"></div> SEDANG</span>
                                            @else
                                                <span class="badge badge-info"><div class="badge-dot"></div> RENDAH</span>
                                            @endif
                                        </div>
                                        @if($item->confidence_score)
                                            <div style="font-size:0.75rem; color:var(--text-primary); font-weight:700;">Keyakinan AI: <span style="font-weight:800;">{{ round($item->confidence_score * 100) }}%</span></div>
                                        @endif
                                    </td>
                                    <td class="td-center">
                                        <div id="status-validasi-{{ $item->id }}">
                                            @if($item->status_validasi == 'Valid')
                                                <span class="badge badge-success">VALID</span>
                                            @elseif($item->status_validasi == 'False detection')
                                                <span class="badge badge-danger">DIABAIKAN</span>
                                            @else
                                                <span class="badge badge-warning">MENUNGGU</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="td-center">
                                        <div class="aksi-wrapper">
                                            @if($item->status_validasi != 'Valid' && $item->status_validasi != 'False detection')
                                            <div class="btn-quick-group" style="display: flex; gap: 5px;">
                                                <form action="{{ route('dashboard.updateValidation', $item->id) }}" method="POST" style="flex: 1; margin: 0;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_validasi" value="Valid">
                                                    <button type="submit" class="btn-quick valid" style="width: 100%; border:none;">Valid</button>
                                                </form>

                                                <form action="{{ route('dashboard.updateValidation', $item->id) }}" method="POST" style="flex: 1; margin: 0;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status_validasi" value="False detection">
                                                    <button type="submit" class="btn-quick invalid" style="width: 100%; border:none;">Abaikan</button>
                                                </form>
                                            </div>
                                            @endif
                                            <a href="{{ route('dashboard.show', $item->id) }}" class="action-link">Detail Data</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                                        <svg width="40" height="40" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24" style="margin-bottom: 0.5rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <h3 style="color: var(--text-primary); font-weight: 800; font-size: 1rem;">Aman Terkendali</h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 1rem;">
                    {{ $detections->links('pagination::bootstrap-4') }}
                </div>
            </section>

            <aside style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="card">
                    <h3 class="section-title" style="font-size: 1rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        Grafik Validasi
                    </h3>
                    <div class="chart-container">
                        <canvas id="statsChart"></canvas>
                    </div>
                </div>

                @php $latest = App\Models\Detection::latest('waktu_kejadian')->first(); @endphp
                @if($latest)
                <div class="card latest-card">
                    <h3 class="section-title" style="font-size: 1rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Tangkapan Terakhir
                    </h3>
                    @php 
                        $ext = strtolower(pathinfo($latest->gambar_bukti, PATHINFO_EXTENSION)); 
                        $latestImagePath = str_starts_with($latest->gambar_bukti, 'images/') ? asset($latest->gambar_bukti) : asset('storage/'.$latest->gambar_bukti);
                    @endphp
                    @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                        <video src="{{ $latestImagePath }}" class="latest-card-img" muted loop autoplay></video>
                    @else
                        <img src="{{ $latestImagePath }}" class="latest-card-img">
                    @endif
                    <div class="latest-info">
                        <p style="font-weight: 800; color: var(--text-primary); margin-bottom: 4px; display:flex; align-items:center; gap:5px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ $latest->lokasi }}
                        </p>
                        <p style="font-weight: 600; font-size: 0.8rem; display:flex; align-items:center; gap:5px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $latest->waktu_kejadian ? $latest->waktu_kejadian->format('H:i, d M Y') : '-' }}
                        </p>
                    </div>
                    <a href="{{ route('dashboard.show', $latest->id) }}" class="btn btn-outline" style="width:100%; text-align:center; margin-top: 0.8rem; display:block;">Cek Detail</a>
                </div>
                @endif

                <div class="card sidebar-rawan">
                    <h3 class="section-title" style="font-size: 1rem; border:none; margin-bottom: 0.3rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Waktu Paling Rawan
                    </h3>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            @php
                                $jamAwal = $jamTersibuk;
                                $jamAkhir = $jamTersibuk != '-' ? str_pad((intval(substr($jamTersibuk, 0, 2)) + 1) % 24, 2, '0', STR_PAD_LEFT) . ':00' : '-';
                            @endphp
                            <div style="font-size:2rem; font-weight:800; color:var(--text-primary);">
                                {{ $jamTersibuk != '-' ? $jamAwal . ' - ' . $jamAkhir : '-' }}
                            </div>
                            <div style="font-size:0.8rem; color:var(--text-secondary); font-weight:700;">JAM PUNCAK</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:1.5rem; font-weight:800; color:var(--text-primary);">{{ $totalJamTersibuk }}</div>
                            <div style="font-size:0.8rem; color:var(--text-secondary); font-weight:700; text-transform: uppercase;">Kejadian</div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div id="mediaModal" class="modal-overlay" onclick="closeModal()">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script>
        function clearTanggalAndSubmit() { document.getElementById('tanggal').value = ''; document.getElementById('filterForm').submit(); }
        function clearRentangAndSubmit() { document.getElementById('rentang_waktu').value = ''; document.getElementById('filterForm').submit(); }

        function openModal(type, src) {
            const body = document.getElementById('modalBody');
            if (type === 'video') {
                body.innerHTML = `<video src="${src}" controls autoplay loop></video>`;
            } else {
                body.innerHTML = `<img src="${src}">`;
            }
            document.getElementById('mediaModal').classList.add('active');
        }

        function closeModal() { document.getElementById('mediaModal').classList.remove('active'); setTimeout(() => { document.getElementById('modalBody').innerHTML = ''; }, 300); }
        document.addEventListener('keydown', function(event) { if (event.key === "Escape") closeModal(); });

        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('statsChart');
            if(canvas) {
                new Chart(canvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Menunggu', 'Valid', 'Dibatalkan'],
                        datasets: [{
                            data: [{{ $menungguValidasi }}, {{ $valid }}, {{ $diabaikan }}],
                            backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                            borderRadius: 8, barThickness: 32
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        plugins: { legend: { display: false } }, 
                        scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } } 
                    }
                });
            }
        });
    </script>
</body>
</html>