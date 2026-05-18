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
            --bg-color: #F7F9F7;
            --surface-color: #FFFFFF;
            --surface-hover: #F8FAF9;
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
            --accent-info: #3B82F6;
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
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 2rem;
            line-height: 1.5;
        }

        .container { max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem; }

        .card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        
        .card:hover { box-shadow: var(--shadow-md); }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 1rem; }

        .brand-title {
            font-size: 2rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.02em; margin-bottom: 0.2rem;
        }

        .header-desc { color: var(--text-secondary); font-size: 0.95rem; font-weight: 500; }

        .header-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 0.75rem; }

        .system-health { display: flex; gap: 1rem; margin-bottom: 0.25rem; }
        .health-indicator { display: flex; align-items: center; gap: 0.3rem; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); }
        .health-dot { width: 8px; height: 8px; background-color: var(--accent-success); border-radius: 50%; }

        .time-live {
            display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--text-secondary);
            font-weight: 500;
        }

        /* Buttons */
        .btn-group { display: flex; gap: 0.75rem; }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; padding: 0.7rem 1.25rem;
            border-radius: var(--radius-md); font-weight: 600; font-size: 0.95rem; cursor: pointer; text-decoration: none;
            transition: all 0.2s ease; border: 1px solid transparent;
        }

        .btn-primary { background-color: var(--accent-button); color: #fff; box-shadow: 0 4px 10px rgba(46, 125, 50, 0.2); }
        .btn-primary:hover { background-color: var(--accent-button-hover); transform: translateY(-1px); box-shadow: 0 6px 15px rgba(46, 125, 50, 0.3); }

        .btn-outline { background-color: var(--surface-color); color: var(--accent-primary); border-color: var(--border-color); box-shadow: var(--shadow-sm); }
        .btn-outline:hover { background-color: var(--surface-hover); border-color: var(--accent-primary); color: var(--accent-primary); }

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

        /* Main Content Grid */
        .content-grid { display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem; }
        @media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; } }

        .section-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--accent-primary); display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.75rem; }

        /* Forms & Filters */
        .filter-form { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; align-items: center; flex-wrap: wrap; }
        .filter-form select, .filter-form input { padding: 0.6rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; background: var(--surface-color); font-size: 0.9rem; color: var(--text-primary); outline: none; transition: border-color 0.2s; box-shadow: var(--shadow-sm); }
        .filter-form select:focus, .filter-form input:focus { border-color: var(--accent-primary); }

        /* Table */
        .table-container { overflow-x: auto; border-radius: var(--radius-lg); border: 1px solid var(--border-color); background: var(--surface-color); box-shadow: var(--shadow-sm); }
        table { width: 100%; border-collapse: separate; border-spacing: 0; text-align: left; }
        th { padding: 1.25rem 1rem; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--text-secondary); background: #F8FAFC; border-bottom: 1px solid var(--border-color); letter-spacing: 0.05em; }
        td { padding: 1.25rem 1rem; font-size: 0.95rem; border-bottom: 1px solid var(--border-color); vertical-align: middle; transition: background 0.2s; }
        tbody tr:hover td { background-color: var(--surface-hover); }

        .thumbnail { width: 100px; height: 75px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .thumbnail:hover { transform: scale(1.15) translateY(-5px); box-shadow: var(--shadow-md); z-index: 10; position: relative; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 0.4rem 0.85rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; gap: 0.35rem; }
        .badge-danger { background: rgba(244, 63, 94, 0.1); color: var(--accent-danger); }
        .badge-warning { background: rgba(249, 115, 22, 0.1); color: var(--accent-warning); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--accent-success); }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-danger .badge-dot { background: var(--accent-danger); }
        .badge-warning .badge-dot { background: var(--accent-warning); }
        .badge-success .badge-dot { background: var(--accent-success); }

        .action-link { color: var(--accent-button); text-decoration: none; font-weight: 600; font-size: 0.85rem; padding: 0.5rem 1rem; border-radius: 8px; background: rgba(16, 185, 129, 0.05); transition: all 0.2s; display: inline-block; border: 1px solid transparent; }
        .action-link:hover { background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); }

        .btn-quick { background: none; border: 1px solid var(--border-color); border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.35rem; color: var(--text-secondary); }
        .btn-quick:hover { background: var(--surface-hover); border-color: var(--text-secondary); color: var(--text-primary); }
        .btn-quick.valid:hover { background: rgba(16, 185, 129, 0.1); border-color: var(--accent-success); color: var(--accent-success); }
        .btn-quick.invalid:hover { background: rgba(244, 63, 94, 0.1); border-color: var(--accent-danger); color: var(--accent-danger); }

        /* Responsive Table for Mobile */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { position: absolute; top: -9999px; left: -9999px; }
            tr { border: 1px solid var(--border-color); border-radius: var(--radius-lg); margin-bottom: 1rem; background: var(--surface-color); box-shadow: var(--shadow-sm); padding: 1rem; }
            td { border: none; padding: 0.5rem 0; position: relative; padding-left: 40%; border-bottom: 1px solid #f1f5f9; }
            td:last-child { border-bottom: none; }
            td:before { position: absolute; top: 0.5rem; left: 0; width: 35%; padding-right: 10px; white-space: nowrap; font-weight: 700; font-size: 0.75rem; color: var(--text-secondary); }
            td:nth-of-type(1):before { content: "Bukti"; }
            td:nth-of-type(2):before { content: "Info & Lokasi"; }
            td:nth-of-type(3):before { content: "AI Analysis"; }
            td:nth-of-type(4):before { content: "Status Admin"; }
            td:nth-of-type(5):before { content: "Aksi"; }
        }

        /* Sidebar Elements */
        .sidebar-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .sidebar-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: var(--surface-hover); border-radius: 8px; border: 1px solid var(--border-color); }
        .sidebar-item h4 { font-size: 0.95rem; font-weight: 600; color: var(--text-primary); }
        .sidebar-count { font-size: 1.25rem; font-weight: 700; color: var(--accent-primary); }
        
        .latest-card img, .latest-card video { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem; border: 1px solid var(--border-color); }
        .latest-info p { margin-bottom: 0.4rem; font-size: 0.9rem; color: var(--text-secondary); }
        .latest-info strong { color: var(--text-primary); }

        /* Popup Modal - Improved Layout */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 9999;
            justify-content: center; align-items: center; padding: 2rem;
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.active { opacity: 1; display: flex; }
        
        .modal-container {
            background: var(--surface-color); border-radius: var(--radius-lg); width: 100%; max-width: 900px;
            max-height: 90vh; display: flex; flex-direction: column; box-shadow: var(--shadow-lg);
            transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }
        .modal-overlay.active .modal-container { transform: scale(1); }

        .modal-header {
            padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid var(--border-color); background: var(--surface-hover);
        }
        .modal-title { font-weight: 700; font-size: 1.2rem; color: var(--accent-primary); margin: 0; }
        .modal-close {
            background: transparent; border: none; color: var(--text-secondary); font-size: 1.5rem;
            cursor: pointer; line-height: 1; padding: 0.25rem; border-radius: 6px; transition: background 0.2s;
        }
        .modal-close:hover { background: rgba(0,0,0,0.05); color: var(--accent-danger); }
        
        .modal-body {
            padding: 1.5rem; overflow-y: auto; display: flex; justify-content: center; align-items: center;
            background: #F1F5F9; min-height: 300px;
        }
        .modal-body img, .modal-body video {
            max-width: 100%; max-height: 70vh; border-radius: 8px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color);
        }

        /* Chart Container */
        .chart-container { position: relative; height: 250px; width: 100%; margin-top: 1rem; }

        /* Pagination */
        .pagination { display: flex; list-style: none; gap: 0.5rem; justify-content: center; margin: 0; padding: 0; }
        .page-item .page-link {
            display: flex; align-items: center; justify-content: center; min-width: 36px; height: 36px;
            border-radius: 8px; background: var(--surface-color); border: 1px solid var(--border-color);
            color: var(--text-primary); text-decoration: none; font-size: 0.9rem; font-weight: 600; box-shadow: var(--shadow-sm);
        }
        .page-item.active .page-link { background: var(--accent-primary); border-color: var(--accent-primary); color: white; }
        .page-item:not(.active):not(.disabled) .page-link:hover { background: var(--surface-hover); border-color: var(--accent-primary); color: var(--accent-primary); }
        .page-item.disabled .page-link { opacity: 0.5; background: var(--surface-hover); }

    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <header class="header">
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
            <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); padding: 1rem; border-radius: 8px; color: var(--accent-success); font-weight: 500; display:flex; gap: 0.5rem; align-items:center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Summary Cards -->
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
                    <span class="stat-value">{{ $totalTerverifikasi }}</span>
                    <span class="stat-desc">Pelanggaran terkonfirmasi</span>
                </div>
                <div class="card stat-card danger">
                    <span class="stat-title">Dibatalkan (False)</span>
                    <span class="stat-value">{{ $totalFalseDetection }}</span>
                    <span class="stat-desc">Bukan pelanggaran</span>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Data Table -->
            <section class="card" style="display: flex; flex-direction: column;">
                <h2 class="section-title">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Data Pantauan AI
                </h2>
                
                <form class="filter-form" method="GET" action="{{ route('dashboard.index') }}">
                    <select name="rentang_waktu">
                        <option value="">Semua Waktu</option>
                        <option value="Hari Ini" {{ request('rentang_waktu') == 'Hari Ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="Bulan Ini" {{ request('rentang_waktu') == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="Tahun Ini" {{ request('rentang_waktu') == 'Tahun Ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" title="Pilih Tanggal">
                    <input type="text" name="lokasi" value="{{ request('lokasi') }}" placeholder="Cari Lokasi..." style="flex-grow: 1; min-width: 150px;">
                    <select name="status">
                        <option value="">Semua Status AI</option>
                        <option value="Tidak Terindikasi" {{ request('status') == 'Tidak Terindikasi' ? 'selected' : '' }}>Tidak Terindikasi</option>
                        <option value="Perlu Validasi" {{ request('status') == 'Perlu Validasi' ? 'selected' : '' }}>Perlu Validasi</option>
                        <option value="Indikasi Pelanggaran Tinggi" {{ request('status') == 'Indikasi Pelanggaran Tinggi' ? 'selected' : '' }}>Indikasi Pelanggaran Tinggi</option>
                    </select>
                    <select name="per_page" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1rem;">Filter</button>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-outline" style="padding: 0.6rem 1rem;">Reset</a>
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
                                            @php $ext = strtolower(pathinfo($item->gambar_bukti, PATHINFO_EXTENSION)); @endphp
                                            @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                                                <video src="{{ asset('storage/'.$item->gambar_bukti) }}" class="thumbnail" onclick="openModal('video', '{{ asset('storage/'.$item->gambar_bukti) }}')" muted></video>
                                            @else
                                                <img src="{{ asset('storage/'.$item->gambar_bukti) }}" class="thumbnail" onclick="openModal('img', '{{ asset('storage/'.$item->gambar_bukti) }}')" alt="Bukti">
                                            @endif
                                        @else
                                            <div class="thumbnail" style="background:#F1F5F9; display:flex; align-items:center; justify-content:center; color:#94A3B8; font-size:10px;">N/A</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight:600; color:var(--text-primary);">📍 {{ $item->lokasi }}</div>
                                        <div style="font-size:0.85rem; color:var(--text-secondary); margin-top: 4px;">🕒 {{ $item->waktu_kejadian ? $item->waktu_kejadian->format('d M Y H:i') : '-' }}</div>
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
                                                <span class="badge badge-warning" style="background: var(--surface-hover); color: var(--text-secondary);"><div class="badge-dot" style="background: var(--text-secondary);"></div> Menunggu</span>
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
                                    <td colspan="5" style="text-align: center; padding: 4rem 1rem; color: var(--text-secondary); background: var(--surface-color);">
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

            <!-- Sidebar -->
            <aside style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <!-- Grafik Aktivitas -->
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
                        @php $ext = strtolower(pathinfo($latest->gambar_bukti, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                            <video src="{{ asset('storage/'.$latest->gambar_bukti) }}" muted loop autoplay></video>
                        @else
                            <img src="{{ asset('storage/'.$latest->gambar_bukti) }}" alt="Terakhir">
                        @endif
                    @endif
                    <div class="latest-info">
                        <p><strong>Waktu:</strong> {{ $latest->waktu_kejadian ? $latest->waktu_kejadian->format('H:i, d M Y') : '-' }}</p>
                        <p><strong>Lokasi:</strong> {{ $latest->lokasi }}</p>
                    </div>
                    <a href="{{ route('dashboard.show', $latest->id) }}" class="btn btn-outline" style="width:100%; text-align:center; margin-top: 0.5rem;">Validasi Sekarang</a>
                </div>
                @endif

                <div class="card" style="border-left: 4px solid var(--accent-info); background: #F0F9FF;">
                    <h2 class="section-title" style="font-size: 1rem; color: var(--accent-info); border-color: #BAE6FD;">
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

    <!-- Modal Image Viewer - Improved -->
    <div id="mediaModal" class="modal-overlay" onclick="closeModal()">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">Bukti Visual</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Media will be inserted here -->
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
            }, 300); // Wait for transition
        }

        // Escape key to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });

        // Update Live Time (simplified format)
        function updateTime() {
            const now = new Date();
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            
            document.getElementById('current-time').textContent = `${day} ${monthName} ${year} • ${h}:${m} WIB`;
        }
        setInterval(updateTime, 60000);

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
                    // Sembunyikan tombol action
                    const actionCell = container.closest('tr').querySelector('.btn-quick').parentElement;
                    if(actionCell) actionCell.style.display = 'none';
                }
            })
            .catch(err => console.error(err));
        }

        // Chart.js Setup
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('statsChart').getContext('2d');
            
            // Mengambil data dari variabel PHP yang sudah ada di Dashboard
            const data = {
                labels: ['Indikasi Tinggi', 'Perlu Validasi', 'Aman (Abaikan)'],
                datasets: [{
                    label: 'Jumlah Deteksi',
                    data: [{{ $aktivitasKuat }}, {{ $perluValidasi }}, {{ $tidakTerindikasi }}],
                    backgroundColor: [
                        'rgba(220, 38, 38, 0.8)', // Danger
                        'rgba(245, 158, 11, 0.8)', // Warning
                        'rgba(16, 185, 129, 0.8)'  // Success
                    ],
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
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(31, 41, 55, 0.9)',
                            padding: 10,
                            titleFont: { family: "'Inter', sans-serif", size: 13 },
                            bodyFont: { family: "'Inter', sans-serif", size: 14, weight: 'bold' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false },
                            ticks: { precision: 0, font: { family: "'Inter', sans-serif" } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: "'Inter', sans-serif", size: 11 } }
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });
    </script>
</body>
</html>
