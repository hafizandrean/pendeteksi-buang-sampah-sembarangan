<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DetectionController extends Controller
{
    public function index(): View
    {
        $detections = Detection::latest()->get();

        return \view('dashboard.index', compact('detections'));
    }

    public function create(): View
    {
        return \view('dashboard.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
            'waktu_kejadian' => 'required|date',
            'gambar_bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jenis_bukti' => 'required|string|max:255',
            'status_indikasi' => 'required|string|max:255',
            'status_validasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
        ]);

        if ($request->hasFile('gambar_bukti')) {
            $validated['gambar_bukti'] = $request->file('gambar_bukti')->store('bukti', 'public');
        }

        $validated['status_validasi'] = $validated['status_validasi'] ?? 'Belum divalidasi';

        Detection::create($validated);

        return \redirect()
            ->route('dashboard.index')
            ->with('success', 'Data deteksi berhasil diupload.');
    }

    public function show(Detection $detection): View
    {
        return \view('dashboard.show', compact('detection'));
    }

    public function updateValidation(Request $request, Detection $detection): RedirectResponse
    {
        $validated = $request->validate([
            'status_validasi' => 'required|string|max:255',
            'tindak_lanjut' => 'nullable|string',
        ]);

        $detection->update($validated);

        return \redirect()
            ->route('dashboard.show', $detection->id)
            ->with('success', 'Status validasi berhasil diperbarui.');
    }

    public function exportCsv(): StreamedResponse
    {
        $detections = Detection::latest()->get();

        $filename = 'data_deteksi.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($detections) {
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

            foreach ($detections as $detection) {
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
}
