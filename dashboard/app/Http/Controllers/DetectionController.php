<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DetectionController extends Controller
{
    public function index(Request $request)
    {

        $query = Detection::query();

        // Filters
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_kejadian', $request->tanggal);
        } elseif ($request->filled('rentang_waktu')) {
            if ($request->rentang_waktu == 'Hari Ini') {
                $query->whereDate('waktu_kejadian', today());
            } elseif ($request->rentang_waktu == 'Bulan Ini') {
                $query->whereMonth('waktu_kejadian', today()->month)
                      ->whereYear('waktu_kejadian', today()->year);
            } elseif ($request->rentang_waktu == 'Tahun Ini') {
                $query->whereYear('waktu_kejadian', today()->year);
            }
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'LIKE', '%' . $request->lokasi . '%');
        }
        if ($request->filled('status')) {
            $query->where('status_indikasi', $request->status);
        }
        if ($request->filled('status_admin')) {
            $query->where('status_validasi', $request->status_admin);
        }

        // Urutkan berdasarkan ID terbaru (paling baru di atas)
        $query->orderByDesc('id');

        $perPage = $request->input('per_page', 10);
        $detections = $query->paginate($perPage)->withQueryString();

        // Overall Stats
        $totalDeteksi = Detection::count();
        $lokasiRawanObj = Detection::select('lokasi')->groupBy('lokasi')->orderByRaw('COUNT(*) DESC')->first();
        $lokasiRawan = $lokasiRawanObj ? $lokasiRawanObj->lokasi : '-';
        $totalTerverifikasi = Detection::where('status_validasi', 'Valid')->count();
        $totalFalseDetection = Detection::where('status_validasi', 'False detection')->count();

        // Stats Hari Ini (Unused, removed to save memory)

        // Statistik Validasi Admin (Untuk Grafik)
        $menungguValidasi = Detection::whereNotIn('status_validasi', ['Valid', 'False detection'])->count();
        $valid = Detection::where('status_validasi', 'Valid')->count();
        $diabaikan = Detection::where('status_validasi', 'False detection')->count();

        // Jam Tersibuk
        $jamTersibukObj = Detection::selectRaw('HOUR(waktu_kejadian) as jam, COUNT(*) as total')
            ->whereNotNull('waktu_kejadian')
            ->groupBy('jam')
            ->orderByDesc('total')
            ->first();
            
        $jamTersibuk = $jamTersibukObj ? str_pad($jamTersibukObj->jam, 2, '0', STR_PAD_LEFT) . ':00' : '-';
        $totalJamTersibuk = $jamTersibukObj ? $jamTersibukObj->total : 0;

        return view('dashboard.index', compact(
            'detections',
            'totalDeteksi',
            'lokasiRawan',
            'totalTerverifikasi',
            'totalFalseDetection',
            'menungguValidasi',
            'valid',
            'diabaikan',
            'jamTersibuk',
            'totalJamTersibuk'
        ));
    }

    public function create()
    {
        return view('dashboard.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
            'nama_pelaku' => 'nullable|string|max:255',
            'waktu_kejadian' => 'required|date',
            'gambar_bukti' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi,mkv|max:2097152',
            'jenis_bukti' => 'required|string|max:255',
            'status_indikasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $file = $request->file('gambar_bukti');
        $extension = strtolower($file->getClientOriginalExtension());
        $storedPath = $file->store('bukti', 'public');
        $absolutePath = storage_path('app/public/'.$storedPath);

        // --- TAMBAHAN LOGIKA UNTUK VIDEO CCTV (BACKGROUND PROCESS) ---
        if (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
            // Path absolut ke file process_video.py lu (sesuaikan jika posisinya berbeda)
            $pythonScriptPath = base_path('../process_video.py'); 

            // Eksekusi background (jalan di balik layar)
            $command = "python3 " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($absolutePath) . " " . escapeshellarg($request->lokasi) . " > /dev/null 2>&1 &";
            shell_exec($command);

            return redirect()
                ->route('dashboard.index')
                ->with('success', 'Video berhasil diupload! AI sedang mendeteksi pelanggaran di latar belakang.');
        }
        // -------------------------------------------------------------

        // --- LOGIKA UNTUK UPLOAD FOTO ---
        $validated['gambar_bukti'] = $storedPath;
        $validated['status_validasi'] = 'Belum diverifikasi';
        $validated['status_indikasi'] = $validated['status_indikasi'] ?? 'Tidak Terindikasi';

        $aiResult = $this->runAiAssistedDetection($absolutePath);

        if (($aiResult['status'] ?? '') === 'success') {
            $violations = $aiResult['violations'] ?? [];
            $model_version = $aiResult['model_version'] ?? 'YOLOv8 COCO';
            
            if (empty($violations)) {
                $validated['status_indikasi'] = 'Tidak Terindikasi';
                $validated['model_version'] = $model_version;
                Detection::create($validated);
                // Fungsi Telegram dihapus dari sini
            } else {
                foreach ($violations as $idx => $v) {
                    $newValid = $validated;
                    
                    // Logika Baru: Tentukan level berdasarkan keyakinan AI
                    $confidence = $v['confidence_score'] ?? 0;
                    $newValid['confidence_score'] = $confidence;
                    
                    if ($confidence >= 0.75) {
                        $newValid['status_indikasi'] = 'Indikasi Pelanggaran Tinggi';
                    } elseif ($confidence >= 0.50) {
                        $newValid['status_indikasi'] = 'Indikasi Pelanggaran Sedang';
                    } else {
                        $newValid['status_indikasi'] = 'Indikasi Pelanggaran Rendah';
                    }

                    $newValid['kategori_sampah'] = $v['kategori'] ?? 'Indikasi Aktivitas Mencurigakan';
                    $newValid['model_version'] = $model_version;
                    
                    if (!empty($v['frame_out_path'])) {
                        $pathInfo = pathinfo($storedPath);
                        $newValid['gambar_bukti'] = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_frame_' . ($idx + 1) . '.jpg';
                    }
                    
                    Detection::create($newValid);
                    // Fungsi Telegram dihapus dari sini
                }
            }
        } else {
            $validated['status_indikasi'] = 'Tidak Terindikasi';
            $validated['keterangan'] = trim(($validated['keterangan'] ?? '').' | AI gagal dianalisis.', ' |');
            $validated['model_version'] = 'Error/Unknown';
            Detection::create($validated);
            // Fungsi Telegram dihapus dari sini
        }

        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Data deteksi berhasil diupload.');
    }

    public function show(Detection $detection)
    {
        return view('dashboard.show', compact('detection'));
    }

    public function updateValidation(Request $request, Detection $detection)
    {
        $validated = $request->validate([
            'status_validasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string', // Ganti tindak_lanjut jadi keterangan
        ]);

        $detection->update($validated);

        return redirect()
            ->route('dashboard.show', $detection->id)
            ->with('success', 'Status validasi berhasil diperbarui.');
    }

    public function exportCsv()
    {
        $filename = 'data_deteksi.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Lokasi',
                'Nama Pelaku',
                'Waktu Kejadian',
                'Jenis Bukti',
                'Jenis Indikasi',
                'Confidence Score',
                'Status Indikasi',
                'Status Validasi',
                'Keterangan',
                'Tindak Lanjut',
            ]);

            foreach (Detection::latest()->cursor() as $detection) {
                fputcsv($file, [
                    $detection->lokasi,
                    $detection->nama_pelaku,
                    $detection->waktu_kejadian,
                    $detection->jenis_bukti,
                    $detection->kategori_sampah,
                    $detection->confidence_score ? ($detection->confidence_score * 100) . '%' : '',
                    $detection->status_indikasi,
                    $detection->status_validasi,
                    $detection->keterangan,
                    $detection->tindak_lanjut,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function runAiAssistedDetection(string $evidencePath): array
    {
        $pythonBin = env('PYTHON_BIN', 'python');
        $scriptPath = base_path('scripts/ai_assisted_detection.py');

        if (! file_exists($scriptPath)) {
            Log::warning('Script AI tidak ditemukan.', ['script' => $scriptPath]);
            return ['status' => 'error', 'message' => 'Script AI tidak ditemukan.'];
        }

        Log::info('>>> REQUEST UPLOAD MASUK <<<');
        Log::info('Menjalankan AI untuk: '.$evidencePath);

        $command = sprintf(
            '%s %s %s 2>&1',
            escapeshellarg($pythonBin),
            escapeshellarg($scriptPath),
            escapeshellarg($evidencePath)
        );

        $output = [];
        $exitCode = 1;
        exec($command, $output, $exitCode);

        $rawOutput = trim(implode("\n", $output));
        // Hapus log raw output untuk mengurangi beban server
        // Log::info('Raw Output Python: '.$rawOutput);
        // Log::info('Script AI selesai dengan code: '.$exitCode);

        $decoded = json_decode($rawOutput, true);
        if ($exitCode !== 0 || ! is_array($decoded)) {
            return ['status' => 'error', 'message' => $rawOutput ?: 'Output AI tidak valid'];
        }

        return $decoded;
    }
    
    public function sendSingleTelegram(Detection $detection)
     {
            // $this->sendTelegramIfNeeded($detection);

            return back()->with('success', 'Notifikasi Telegram berhasil dikirim.');
    }

    private function sendTelegramIfNeeded(Detection $detection): void
    {
        if (!in_array($detection->status_indikasi, ['Indikasi Pelanggaran Tinggi'])) {
            return;
        }

        $token = (string) env('TELEGRAM_BOT_TOKEN', '8770397403:AAGqk0FxHXbFI_A0VqdCtCQHfCk0THTpE1M');
        $chatId = (string) env('TELEGRAM_CHAT_ID', '-1003941703215');

        if ($token === '' || $chatId === '') {
            Log::warning('Telegram tidak dikirim: TELEGRAM_BOT_TOKEN/TELEGRAM_CHAT_ID belum diisi.');
            return;
        }

        $message = "⚠️ Indikasi pembuangan sampah terdeteksi\n"
            ."Lokasi: {$detection->lokasi}\n"
            ."Waktu: {$detection->waktu_kejadian}\n"
            ."Status: {$detection->status_indikasi}\n"
            ."Kategori: {$detection->kategori_sampah}\n"
            ."Confidence: ".($detection->confidence_score * 100)."%";

        try {
            if ($detection->gambar_bukti && file_exists(storage_path('app/public/' . $detection->gambar_bukti))) {
                Http::timeout(15)
                    ->attach('photo', file_get_contents(storage_path('app/public/' . $detection->gambar_bukti)), 'bukti.jpg')
                    ->post("https://api.telegram.org/bot{$token}/sendPhoto", [
                        'chat_id' => $chatId,
                        'caption' => $message,
                    ]);
            } else {
                Http::asForm()->timeout(15)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim Telegram.', ['error' => $e->getMessage()]);
        }
    }

    public function sendSummaryToTelegram()
    {
        $token = (string) env('TELEGRAM_BOT_TOKEN', '8770397403:AAGqk0FxHXbFI_A0VqdCtCQHfCk0THTpE1M');
        $chatId = (string) env('TELEGRAM_CHAT_ID', '-1003941703215');

        if ($token === '' || $chatId === '') {
            return redirect()->route('dashboard.index')->with('error', 'Token atau Chat ID Telegram belum diatur di .env');
        }

        $today = now()->startOfDay();
        $detections = Detection::where('waktu_kejadian', '>=', $today)
            ->whereIn('status_indikasi', ['Indikasi Pelanggaran Tinggi', 'Perlu Validasi'])
            ->get();

        $total = $detections->count();
        $belumTerdenda = $detections->where('status_validasi', 'Belum diverifikasi')->count();

        $message = "📊 *Ringkasan Pelanggaran Hari Ini*\n\n"
            . "Total pelanggaran: {$total}\n"
            . "Belum diverifikasi: {$belumTerdenda}\n\n"
            . "Mohon segera diverifikasi. Cek detail selengkapnya di sistem SiCCTV Sampah.";

        try {
            Http::asForm()->timeout(15)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
            
            return redirect()->route('dashboard.index')->with('success', 'Ringkasan berhasil dikirim ke Telegram RT!');
        } catch (\Throwable $e) {
            Log::error('Gagal kirim ringkasan Telegram.', ['error' => $e->getMessage()]);
            return redirect()->route('dashboard.index')->with('error', 'Gagal mengirim pesan ke Telegram.');
        }
    }
}