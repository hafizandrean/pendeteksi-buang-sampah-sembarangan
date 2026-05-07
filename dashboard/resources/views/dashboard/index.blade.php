<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiCCTV Sampah - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #F4F8F2;
            --surface-color: rgba(255, 255, 255, 0.85);
            --surface-hover: rgba(241, 248, 233, 0.95);
            --border-color: rgba(224, 224, 224, 0.6);
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --accent-primary: #2E7D32;
            --accent-primary-hover: #1b5e20;
            --accent-button: #4CAF50;
            --accent-button-hover: #388e3c;
            --accent-danger: #ef4444;
            --accent-success: #22c55e;
            --accent-warning: #f59e0b;
            --font-family: 'Inter', sans-serif;
            --glass-blur: blur(12px);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 2rem;
            line-height: 1.5;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .glass-panel {
            background: var(--surface-color);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }
        
        .glass-panel:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-title h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent-primary);
            margin-bottom: 0.25rem;
        }

        .header-title p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1rem;
        }

        .time-live {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            color: var(--accent-danger);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.25rem 0.75rem;
            background: rgba(229, 57, 53, 0.1);
            border-radius: 9999px;
            animation: pulse 2s infinite;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background-color: var(--accent-danger);
            border-radius: 50%;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(229, 57, 53, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(229, 57, 53, 0); }
            100% { box-shadow: 0 0 0 0 rgba(229, 57, 53, 0); }
        }

        .btn-group {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--accent-button);
            color: #fff;
            box-shadow: 0 4px 14px 0 rgba(76, 175, 80, 0.39);
        }

        .btn-primary:hover {
            background-color: var(--accent-button-hover);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .btn-outline:hover {
            background-color: rgba(46, 125, 50, 0.05);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
            border-left: 4px solid var(--border-color);
        }

        .stat-card.primary { border-left-color: var(--accent-primary); }
        .stat-card.warning { border-left-color: var(--accent-warning); }
        .stat-card.success { border-left-color: var(--accent-success); }
        .stat-card.danger { border-left-color: var(--accent-danger); }

        .stat-title {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-desc {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: var(--accent-primary);
        }

        /* Forms */
        .filter-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.25rem;
            align-items: center;
        }
        
        .filter-form select, .filter-form input {
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            background: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            color: var(--text-primary);
            transition: all 0.2s ease;
            outline: none;
        }
        
        .filter-form select:focus, .filter-form input:focus {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: rgba(255,255,255,0.5);
        }

        th {
            text-align: left;
            padding: 1.25rem 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border-color);
            font-weight: 700;
            background: rgba(244, 248, 242, 0.8);
        }

        td {
            padding: 1.25rem 1rem;
            font-size: 0.95rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            color: var(--text-primary);
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: var(--surface-hover);
        }

        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-danger { background: rgba(229, 57, 53, 0.1); color: var(--accent-danger); }
        .badge-warning { background: rgba(251, 140, 0, 0.1); color: var(--accent-warning); }
        .badge-success { background: rgba(67, 160, 71, 0.1); color: var(--accent-success); }
        .badge-info { background: rgba(46, 125, 50, 0.1); color: var(--accent-primary); }

        .action-link {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .sidebar-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .sidebar-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f9fbf9;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .sidebar-item h4 {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .sidebar-count {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--accent-primary);
        }
        
        .latest-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .latest-info p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        /* Modern Pagination CSS */
        .pagination {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            justify-content: center;
            margin: 0;
            padding: 0;
            align-items: center;
            flex-wrap: wrap;
        }

        .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.5rem;
            border-radius: 8px;
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .page-item.active .page-link {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
            box-shadow: 0 4px 10px rgba(46, 125, 50, 0.3);
        }

        .page-item:not(.active):not(.disabled) .page-link:hover {
            background: var(--surface-hover);
            border-color: var(--accent-primary);
            color: var(--accent-primary);
            transform: translateY(-1px);
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f8fafc;
        }

    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-title">
                <h1>SiCCTV Sampah</h1>
                <p>Sistem Monitoring Pertanian & Lingkungan</p>
            </div>
            <div class="header-actions">
                <div class="time-live">
                    <span id="current-time">{{ now()->isoFormat('ddd, D MMM YYYY, HH:mm:ss') }}</span>
                    <div class="live-indicator">
                        <div class="live-dot"></div>
                        LIVE
                    </div>
                </div>
                <div class="btn-group">
                    <a href="{{ route('dashboard.export') }}" class="btn btn-outline">
                        Export CSV
                    </a>
                    <a href="{{ route('dashboard.create') }}" class="btn btn-primary">
                        Upload Bukti
                    </a>
                </div>
            </div>
        </header>

        @if (session('success'))
            <div style="background: rgba(67, 160, 71, 0.1); border: 1px solid var(--accent-success); padding: 1rem; border-radius: 8px; color: var(--accent-success);">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background: rgba(229, 57, 53, 0.1); border: 1px solid var(--accent-danger); padding: 1rem; border-radius: 8px; color: var(--accent-danger);">
                {{ session('error') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <section>
            <h2 class="section-title">STATISTIK DASHBOARD</h2>
            <div class="summary-grid">
                <div class="glass-panel stat-card warning">
                    <span class="stat-title">Total Deteksi</span>
                    <span class="stat-value">{{ $totalDeteksi }}</span>
                    <span class="stat-desc">Keseluruhan data</span>
                </div>
                <div class="glass-panel stat-card primary">
                    <span class="stat-title">Lokasi Rawan</span>
                    <span class="stat-value" style="font-size: 1.5rem; line-height: 1.3;">{{ $lokasiRawan }}</span>
                    <span class="stat-desc">Paling sering dilanggar</span>
                </div>
                <div class="glass-panel stat-card success">
                    <span class="stat-title">Terverifikasi Valid</span>
                    <span class="stat-value">{{ $totalTerverifikasi }}</span>
                    <span class="stat-desc">Pelanggaran dikonfirmasi</span>
                </div>
                <div class="glass-panel stat-card danger">
                    <span class="stat-title">False Detection</span>
                    <span class="stat-value">{{ $totalFalseDetection }}</span>
                    <span class="stat-desc">Deteksi keliru oleh AI</span>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Data Table -->
            <section class="glass-panel" style="overflow: hidden;">
                <h2 class="section-title">DATA PELANGGARAN</h2>
                
                <form class="filter-form" method="GET" action="{{ route('dashboard.index') }}" style="flex-wrap: wrap;">
                    <select name="rentang_waktu">
                        <option value="">Semua Waktu</option>
                        <option value="Hari Ini" {{ request('rentang_waktu') == 'Hari Ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="Bulan Ini" {{ request('rentang_waktu') == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="Tahun Ini" {{ request('rentang_waktu') == 'Tahun Ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                    <input type="text" name="lokasi" value="{{ request('lokasi') }}" placeholder="Cari Lokasi..." style="flex-grow: 1; min-width: 200px;">
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="Aman" {{ request('status') == 'Aman' ? 'selected' : '' }}>Aman</option>
                        <option value="Terindikasi membuang sampah" {{ request('status') == 'Terindikasi membuang sampah' ? 'selected' : '' }}>Terindikasi membuang sampah</option>
                        <option value="Mencurigakan" {{ request('status') == 'Mencurigakan' ? 'selected' : '' }}>Mencurigakan</option>
                    </select>
                    <select name="per_page">
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Baris</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 Baris</option>
                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 Baris</option>
                        <option value="500" {{ request('per_page') == '500' ? 'selected' : '' }}>500 Baris</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">Filter</button>
                    <a href="{{ route('dashboard.index') }}" class="btn btn-outline" style="padding: 0.6rem 1.25rem;">Reset</a>
                </form>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Bukti</th>
                                <th>Pelaku / Waktu</th>
                                <th>Lokasi</th>
                                <th>Kategori / AI Score</th>
                                <th>Status AI</th>
                                <th>Status Verifikasi</th>
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
                                            @endphp
                                            @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                                                <video src="{{ asset('storage/'.$item->gambar_bukti) }}" class="thumbnail" muted></video>
                                            @else
                                                <img src="{{ asset('storage/'.$item->gambar_bukti) }}" class="thumbnail" alt="Bukti">
                                            @endif
                                        @else
                                            <div class="thumbnail" style="background:#eee; display:flex; align-items:center; justify-content:center; color:#999; font-size:10px;">No Image</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight:600; color:var(--text-primary)">{{ $item->nama_pelaku ?? 'Tidak Diketahui' }}</div>
                                        <div style="font-size:0.8rem; color:var(--text-secondary)">{{ $item->waktu_kejadian ? $item->waktu_kejadian->format('d M Y H:i') : '-' }}</div>
                                    </td>
                                    <td>{{ $item->lokasi }}</td>
                                    <td>
                                        <div>{{ $item->kategori_sampah ?? '-' }}</div>
                                        @if($item->confidence_score)
                                            <div style="font-size:0.8rem; color:var(--accent-warning); font-weight:600;">Conf: {{ $item->confidence_score * 100 }}%</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array($item->status_indikasi, ['Terindikasi membuang sampah', 'Mencurigakan']))
                                            <span class="badge badge-danger">{{ $item->status_indikasi }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $item->status_indikasi }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status_validasi == 'Valid')
                                            <span class="badge badge-success">Valid</span>
                                        @elseif($item->status_validasi == 'False detection')
                                            <span class="badge badge-danger">False</span>
                                        @else
                                            <span class="badge badge-warning">Belum diverifikasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.show', $item->id) }}" class="action-link">Detail & Verifikasi</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                        <svg style="width:48px; height:48px; margin:0 auto 1rem auto; display:block; opacity:0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Tidak ada pelanggaran yang ditemukan sesuai filter saat ini.
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
                
                @php
                    $latest = App\Models\Detection::latest('waktu_kejadian')->first();
                @endphp
                @if($latest)
                <div class="glass-panel latest-card">
                    <h2 class="section-title">Deteksi Terakhir</h2>
                    @if($latest->gambar_bukti)
                        @php
                            $ext = strtolower(pathinfo($latest->gambar_bukti, PATHINFO_EXTENSION));
                        @endphp
                        @if(in_array($ext, ['mp4', 'mov', 'avi', 'mkv']))
                            <video src="{{ asset('storage/'.$latest->gambar_bukti) }}" muted style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem;"></video>
                        @else
                            <img src="{{ asset('storage/'.$latest->gambar_bukti) }}" alt="Terakhir">
                        @endif
                    @endif
                    <div class="latest-info">
                        <p><strong>Waktu:</strong> {{ $latest->waktu_kejadian ? $latest->waktu_kejadian->format('d M Y H:i:s') : '-' }}</p>
                        <p><strong>Lokasi:</strong> {{ $latest->lokasi }}</p>
                        <p><strong>Status:</strong> <span style="color:var(--accent-warning)">{{ $latest->status_indikasi }}</span></p>
                    </div>
                    <a href="{{ route('dashboard.show', $latest->id) }}" class="btn btn-outline" style="width:100%; text-align:center;">Lihat Detail</a>
                </div>
                @endif

                <div class="glass-panel">
                    <h2 class="section-title">Kategori Sampah</h2>
                    <div class="sidebar-list">
                        <div class="sidebar-item">
                            <h4>Botol</h4>
                            <span class="sidebar-count">{{ $botol }}</span>
                        </div>
                        <div class="sidebar-item">
                            <h4>Plastik/Gelas</h4>
                            <span class="sidebar-count">{{ $plastik }}</span>
                        </div>
                        <div class="sidebar-item">
                            <h4>Lainnya</h4>
                            <span class="sidebar-count">{{ $lainnya }}</span>
                        </div>
                    </div>
                </div>

                <div class="glass-panel notification-card" style="flex-direction:column; align-items:flex-start; gap:1rem;">
                    <div class="notif-info">
                        <h2 class="section-title" style="margin-bottom: 0;">Notifikasi Ketua RT</h2>
                        <p>Penerima: Bapak Suyono — RT 05 / RW 02</p>
                    </div>
                    <form action="{{ route('dashboard.send-summary') }}" method="POST" style="margin: 0; width:100%">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width:100%">
                            Kirim Ringkasan Hari Ini
                        </button>
                    </form>
                </div>

            </aside>
        </div>

    </div>

    <script>
        // Update Live Time
        function updateTime() {
            const now = new Date();
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            const dayName = days[now.getDay()];
            const day = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            
            document.getElementById('current-time').textContent = `${dayName}, ${day} ${monthName} ${year}, ${h}:${m}:${s}`;
        }
        
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
