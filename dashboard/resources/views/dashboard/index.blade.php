<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiCCTV Sampah - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b1120;
            --surface-color: rgba(30, 41, 59, 0.7);
            --surface-hover: rgba(51, 65, 85, 0.8);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-primary: #3b82f6;
            --accent-primary-hover: #2563eb;
            --accent-danger: #ef4444;
            --accent-success: #10b981;
            --accent-warning: #f59e0b;
            --font-family: 'Inter', sans-serif;
            --glass-blur: blur(12px);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(239, 68, 68, 0.1) 0px, transparent 50%);
            background-attachment: fixed;
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

        /* Glassmorphism Panel */
        .glass-panel {
            background: var(--surface-color);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-panel:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-title h1 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
            background: rgba(239, 68, 68, 0.1);
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
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
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
            background-color: var(--accent-primary);
            color: #fff;
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39);
        }

        .btn-primary:hover {
            background-color: var(--accent-primary-hover);
            transform: translateY(-1px);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Top Summary Grid */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 4px; height: 100%;
            background: var(--accent-primary);
            border-radius: 4px 0 0 4px;
        }

        .stat-card.danger::before { background: var(--accent-danger); }
        .stat-card.warning::before { background: var(--accent-warning); }
        .stat-card.success::before { background: var(--accent-success); }

        .stat-title {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-desc {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 1.5rem;
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Section Titles */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Age Categories */
        .age-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .age-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .age-info h4 {
            font-size: 1rem;
            font-weight: 500;
        }

        .age-info p {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .age-count {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--accent-primary);
        }

        /* Filters */
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .filter-tab:hover {
            color: var(--text-primary);
            background: rgba(255,255,255,0.05);
        }

        .filter-tab.active {
            color: var(--accent-primary);
            background: rgba(59, 130, 246, 0.1);
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            text-align: left;
            padding: 1rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
        }

        td {
            padding: 1rem;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            vertical-align: middle;
        }

        tbody tr {
            transition: background-color 0.2s;
        }

        tbody tr:hover {
            background-color: rgba(255,255,255,0.02);
        }

        .pelanggar-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-primary), #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
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

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: currentColor;
        }

        .action-link {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        /* Notifikasi Section */
        .notification-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to right, rgba(30, 41, 59, 0.7), rgba(15, 23, 42, 0.8));
            border-left: 4px solid var(--accent-primary);
        }

        .notif-info p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>



    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-title">
                <h1>SiCCTV Sampah</h1>
                <p>Sistem Monitoring Pembuang Sampah Sembarangan</p>
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
            <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.5); padding: 1rem; border-radius: 8px; color: #6ee7b7;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.5); padding: 1rem; border-radius: 8px; color: #fca5a5;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <section>
            <h2 class="section-title">RINGKASAN HARI INI</h2>
            <div class="summary-grid">
                <div class="glass-panel stat-card danger">
                    <span class="stat-title">Total pelanggaran</span>
                    <span class="stat-value">{{ $totalHariIni }}</span>
                    <span class="stat-desc">Hari ini</span>
                </div>
                <div class="glass-panel stat-card warning">
                    <span class="stat-title">Belum terdenda</span>
                    <span class="stat-value">{{ $belumTerdenda }}</span>
                    <span class="stat-desc">Menunggu tindakan</span>
                </div>
                <div class="glass-panel stat-card success">
                    <span class="stat-title">Sudah terdenda</span>
                    <span class="stat-value">{{ $sudahTerdenda }}</span>
                    <span class="stat-desc">Terkirim ke RT</span>
                </div>
                <div class="glass-panel stat-card">
                    <span class="stat-title">Jam tersibuk</span>
                    <span class="stat-value">{{ $jamTersibuk }}</span>
                    <span class="stat-desc">Paling banyak kejadian</span>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="content-grid">
            <!-- Data Table -->
            <section class="glass-panel">
                <h2 class="section-title">DATA PELANGGARAN</h2>
                <div class="filter-tabs">
                    <a href="{{ route('dashboard.index', ['filter' => 'semua']) }}" class="filter-tab {{ $filter === 'semua' ? 'active' : '' }}" style="text-decoration:none;">Semua</a>
                    <a href="{{ route('dashboard.index', ['filter' => 'anak-anak']) }}" class="filter-tab {{ $filter === 'anak-anak' ? 'active' : '' }}" style="text-decoration:none;">Anak-anak</a>
                    <a href="{{ route('dashboard.index', ['filter' => 'remaja']) }}" class="filter-tab {{ $filter === 'remaja' ? 'active' : '' }}" style="text-decoration:none;">Remaja</a>
                    <a href="{{ route('dashboard.index', ['filter' => 'dewasa']) }}" class="filter-tab {{ $filter === 'dewasa' ? 'active' : '' }}" style="text-decoration:none;">Dewasa</a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Pelanggar</th>
                                <th>Waktu</th>
                                <th>Lokasi CCTV</th>
                                <th>Perkiraan Usia</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detections as $item)
                                @php
                                    $ket = strtolower($item->keterangan ?? '');
                                    $kategori = 'Dewasa';
                                    $usia = '25 th';
                                    
                                    if (str_contains($ket, 'anak')) {
                                        $kategori = 'Anak-anak';
                                        $usia = '8 th';
                                    } elseif (str_contains($ket, 'remaja')) {
                                        $kategori = 'Remaja';
                                        $usia = '15 th';
                                    } elseif (str_contains($ket, 'dewasa')) {
                                        $kategori = 'Dewasa';
                                        $usia = '35 th';
                                    }

                                    $isTerdenda = $item->status_validasi !== 'Belum divalidasi';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="pelanggar-info">
                                            <div class="avatar">W</div>
                                            <span>Warga Terdeteksi</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->waktu_kejadian ? $item->waktu_kejadian->format('H.i.s') : '-' }}</td>
                                    <td>{{ $item->lokasi }}</td>
                                    <td>{{ $usia }}</td>
                                    <td>Umum</td>
                                    <td>
                                        @if($isTerdenda)
                                            <span class="badge badge-success">
                                                <span class="badge-dot"></span> Sudah Terdenda
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <span class="badge-dot"></span> Belum Terdenda
                                            </span>
                                        @endif
                                    </td>
                                    <td><a href="{{ route('dashboard.show', $item->id) }}" class="action-link">Detail</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: var(--text-secondary);">Belum ada data pelanggaran hari ini.</td>
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
                <div class="glass-panel">
                    <h2 class="section-title">Kategori usia pelanggar</h2>
                    <div class="age-list">
                        <div class="age-item">
                            <div class="age-info">
                                <h4>Anak-anak</h4>
                                <p>5-12 tahun</p>
                            </div>
                            <span class="age-count">{{ $anakAnak }}</span>
                        </div>
                        <div class="age-item">
                            <div class="age-info">
                                <h4>Remaja</h4>
                                <p>13-17 tahun</p>
                            </div>
                            <span class="age-count">{{ $remaja }}</span>
                        </div>
                        <div class="age-item">
                            <div class="age-info">
                                <h4>Dewasa</h4>
                                <p>18-59 tahun</p>
                            </div>
                            <span class="age-count">{{ $dewasa }}</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Notification RT -->
        <section class="glass-panel notification-card">
            <div class="notif-info">
                <h2 class="section-title" style="margin-bottom: 0;">Notifikasi Ketua RT</h2>
                <p>Penerima: Bapak Suyono — RT 05 / RW 02</p>
            </div>
            <form action="{{ route('dashboard.send-summary') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Kirim semua laporan ke RT
                </button>
            </form>
        </section>
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
