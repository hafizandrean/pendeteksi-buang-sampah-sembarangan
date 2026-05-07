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
        $today = now()->startOfDay();

        // Stats Hari Ini
        $todayQuery = Detection::where('waktu_kejadian', '>=', $today)
            ->where('status_indikasi', 'Mencurigakan');
            
        $totalHariIni = $todayQuery->count();
        $belumTerdenda = (clone $todayQuery)->where('status_validasi', 'Belum divalidasi')->count();
        $sudahTerdenda = $totalHariIni - $belumTerdenda;

        // Jam tersibuk hari ini
        $jamTersibuk = '-';
        $todayDetections = $todayQuery->get(['waktu_kejadian']);
        if ($todayDetections->isNotEmpty()) {
            $hours = $todayDetections->map(function ($d) {
                return $d->waktu_kejadian ? $d->waktu_kejadian->format('H') : null;
            })->filter()->countBy();
            
            if ($hours->isNotEmpty()) {
                $jamTersibuk = $hours->sortDesc()->keys()->first() . '.00';
            }
        }

        // Kategori Usia (Keseluruhan)
        $anakAnak = Detection::where('status_indikasi', 'Mencurigakan')->where('keterangan', 'LIKE', '%anak%')->count();
        $remaja = Detection::where('status_indikasi', 'Mencurigakan')->where('keterangan', 'LIKE', '%remaja%')->count();
        $totalMencurigakan = Detection::where('status_indikasi', 'Mencurigakan')->count();
        $dewasa = max(0, $totalMencurigakan - $anakAnak - $remaja);

        // Data Table dengan Pagination dan Filter
        $filter = $request->query('filter', 'semua');
        
        $query = Detection::where('status_indikasi', 'Mencurigakan')->latest();
        
        if ($filter === 'anak-anak') {
            $query->where('keterangan', 'LIKE', '%anak%');
        } elseif ($filter === 'remaja') {
            $query->where('keterangan', 'LIKE', '%remaja%');
        } elseif ($filter === 'dewasa') {
            $query->where(function($q) {
                $q->where('keterangan', 'LIKE', '%dewasa%')
                  ->orWhere(function($q2) {
                      $q2->where('keterangan', 'NOT LIKE', '%anak%')
                         ->where('keterangan', 'NOT LIKE', '%remaja%');
                  });
            });
        }

        $detections = $query->paginate(10)->withQueryString();

        return view('dashboard.index', compact(
            'detections',
            'totalHariIni',
            'belumTerdenda',
            'sudahTerdenda',
            'jamTersibuk',
            'anakAnak',
            'remaja',
            'dewasa',
            'filter'
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
            'waktu_kejadian' => 'required|date',
            'gambar_bukti' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi,mkv|max:102400',
            'jenis_bukti' => 'required|string|max:255',
            'status_indikasi' => 'nullable|string|max:255',
            'status_validasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $storedPath = $request->file('gambar_bukti')->store('bukti', 'public');
        $validated['gambar_bukti'] = $storedPath;

        $validated['status_validasi'] = $validated['status_validasi'] ?? 'Belum divalidasi';
        $validated['status_indikasi'] = $validated['status_indikasi'] ?? 'Normal';

        $absolutePath = storage_path('app/public/'.$storedPath);
        $aiResult = $this->runAiAssistedDetection($absolutePath);

        if (($aiResult['status'] ?? '') === 'success') {
            $isViolation = (bool) ($aiResult['violation'] ?? false);
            $kategori = (string) ($aiResult['kategori'] ?? 'Umum');

            $validated['status_indikasi'] = $isViolation ? 'Mencurigakan' : 'Normal';
            $validated['keterangan'] = trim(($validated['keterangan'] ?? '').' | AI kategori: '.$kategori, ' |');
        } else {
            $validated['keterangan'] = trim(($validated['keterangan'] ?? '').' | AI gagal dianalisis.', ' |');
        }

        $detection = Detection::create($validated);
        $this->sendTelegramIfNeeded($detection);

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
            'tindak_lanjut' => 'nullable|string',
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
                'Waktu Kejadian',
                'Jenis Bukti',
                'Status Indikasi',
                'Status Validasi',
                'Keterangan',
                'Tindak Lanjut',
            ]);

            foreach (Detection::latest()->cursor() as $detection) {
                fputcsv($file, [
                    $detection->lokasi,
                    $detection->waktu_kejadian,
                    $detection->jenis_bukti,
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
        Log::info('Raw Output Python: '.$rawOutput);
        Log::info('Script AI selesai dengan code: '.$exitCode);

        $decoded = json_decode($rawOutput, true);
        if ($exitCode !== 0 || ! is_array($decoded)) {
            return ['status' => 'error', 'message' => $rawOutput ?: 'Output AI tidak valid'];
        }

        return $decoded;
    }

    private function sendTelegramIfNeeded(Detection $detection): void
    {
        if ($detection->status_indikasi !== 'Mencurigakan') {
            return;
        }

        $token = (string) env('TELEGRAM_BOT_TOKEN', '');
        $chatId = (string) env('TELEGRAM_CHAT_ID', '');

        if ($token === '' || $chatId === '') {
            Log::warning('Telegram tidak dikirim: TELEGRAM_BOT_TOKEN/TELEGRAM_CHAT_ID belum diisi.');
            return;
        }

        $message = "⚠️ Indikasi pembuangan sampah terdeteksi\n"
            ."Lokasi: {$detection->lokasi}\n"
            ."Waktu: {$detection->waktu_kejadian}\n"
            ."Status: {$detection->status_indikasi}";

        try {
            Http::asForm()->timeout(15)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal kirim Telegram.', ['error' => $e->getMessage()]);
        }
    }

    public function sendSummaryToTelegram()
    {
        $token = (string) env('TELEGRAM_BOT_TOKEN', '');
        $chatId = (string) env('TELEGRAM_CHAT_ID', '');

        if ($token === '' || $chatId === '') {
            return redirect()->route('dashboard.index')->with('error', 'Token atau Chat ID Telegram belum diatur di .env');
        }

        $today = now()->startOfDay();
        $detections = Detection::where('waktu_kejadian', '>=', $today)
            ->where('status_indikasi', 'Mencurigakan')
            ->get();

        $total = $detections->count();
        $belumTerdenda = $detections->where('status_validasi', 'Belum divalidasi')->count();

        $message = "📊 *Ringkasan Pelanggaran Hari Ini*\n\n"
            . "Total pelanggaran: {$total}\n"
            . "Belum ditindak: {$belumTerdenda}\n\n"
            . "Mohon segera ditindaklanjuti. Cek detail selengkapnya di sistem SiCCTV Sampah.";

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