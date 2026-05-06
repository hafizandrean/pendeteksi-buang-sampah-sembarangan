# Panduan Final Operasional

## 1) Jalankan Dashboard Laravel

Di terminal 1 (folder root project):

```powershell
.\run_dashboard.ps1
```

Dashboard akan aktif di:

- `http://127.0.0.1:8000/dashboard`

## 2) Jalankan AI Detector

Di terminal 2 (folder root project):

```powershell
.\run_detector.ps1
```

## 2.1) Konfigurasi detector

File konfigurasi detector ada di:

- `.env.detector`

Jika file belum ada, script akan otomatis membuat dari:

- `.env.detector.example`

## 3) Sinkronkan API Key (wajib)

- Di `dashboard/.env`, set:
  - `DETECTION_API_KEY=change_this_ingest_key` (atau ganti dengan key kamu)
- Di `.env.detector`, set:
  - `DETECTION_API_KEY=change_this_ingest_key`

Kedua nilai harus sama.

## 4) Aktifkan Telegram (opsional)

Di `.env.detector` isi:

- `TELEGRAM_TOKEN`
- `TELEGRAM_CHAT_ID`

Jika kosong, deteksi tetap jalan, hanya notifikasi Telegram dinonaktifkan.

## 5) Alur yang sudah berjalan

1. Kamera/CCTV menangkap frame.
2. AI mendeteksi manusia + indikasi objek sampah.
3. Bukti visual disimpan lalu dikirim ke endpoint ingest Laravel.
4. Laravel menyimpan data ke tabel `detections` + file bukti ke storage publik.
5. Dashboard menampilkan data kejadian, detail, validasi, ekspor CSV.
6. Ringkasan mingguan dan rekomendasi tindakan ditampilkan di dashboard.

## 6) Verifikasi final sekali jalan

Jalankan:

```powershell
.\final_check.ps1
```

