import cv2
from ultralytics import YOLO
import datetime
import mysql.connector
import os
import sys

# ================= CONFIG DATABASE =================
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="pendeteksi_db" # Pastikan nama DB sesuai
)
cursor = db.cursor()

# ================= CONFIG AI & FOLDER =================
model = YOLO("/Users/karim/Tugas/Laravel/pendeteksi-buang-sampah-sembarangan/dashboard/app/Models/best.pt")

# Path input video
if len(sys.argv) > 1:
    INPUT_VIDEO_PATH = sys.argv[1] # Menangkap path video yang dikirim dari Laravel
else:
    print("❌ Error: Path video tidak dikirim dari Laravel!")
    exit()

# Folder penyimpanan
SAVE_IMG_DIR = "/Users/karim/Tugas/Laravel/pendeteksi-buang-sampah-sembarangan/dashboard/public/images/detections"
SAVE_VID_DIR = "/Users/karim/Tugas/Laravel/pendeteksi-buang-sampah-sembarangan/dashboard/public/videos/hasil_ai"
os.makedirs(SAVE_IMG_DIR, exist_ok=True)
os.makedirs(SAVE_VID_DIR, exist_ok=True)

# Setup Pembacaan Video
cap = cv2.VideoCapture(INPUT_VIDEO_PATH)
if not cap.isOpened():
    print("❌ Error: Video tidak dapat dibuka!")
    exit()

# Setup Penulisan Video (Output dengan kotak deteksi)
# Menggunakan codec mp4v agar bisa diputar
width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
fps = int(cap.get(cv2.CAP_PROP_FPS))

# Nama file video output berdasarkan waktu rilis AI
output_filename = f"AI_Terdeteksi_{datetime.datetime.now().strftime('%Y%m%d_%H%M%S')}.mp4"
OUTPUT_VIDEO_PATH = f"{SAVE_VID_DIR}/{output_filename}"

fourcc = cv2.VideoWriter_fourcc(*'mp4v')
out = cv2.VideoWriter(OUTPUT_VIDEO_PATH, fourcc, fps, (width, height))

# Logika Cooldown (Karena video offline, kita pakai frame count)
frames_passed = 0
cooldown_frames = fps * 15 # Jeda 15 detik video antar deteksi simpan foto
last_detect_frame = -cooldown_frames

print(f"🚀 Memproses video: {INPUT_VIDEO_PATH}...")

# ================= LOOPING PROSES VIDEO =================
while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break # Video selesai
    
    frames_passed += 1

    if frames_passed % 5 != 0:
        continue

    # Proses AI
    results = model.track(frame, persist=True, verbose=False, conf=0.5)
    
    ada_manusia = False
    ada_sampah = False
    max_sampah_conf = 0.0 # Buat nyimpen skor tertinggi dari sampah

    # Analisis Bounding Box
    for r in results:
        if r.boxes:
            for box in r.boxes:
                cls = int(box.cls[0])
                conf = float(box.conf[0]) # Ambil nilai confidence
                class_name = model.names[cls]

                if class_name == "person":
                    ada_manusia = True
                
                # Masukin semua nama class barang/sampah dari model lu ke dalam kurung siku ini
                if class_name in ["bottle", "cup", "hp", "can", "battery", "trash", "plastic", "paper", "plastic bag"]: 
                    ada_sampah = True
                    if conf > max_sampah_conf:
                        max_sampah_conf = conf # Update skor tertinggi

    # Render frame dengan kotak deteksi
    annotated_frame = results[0].plot()

    # LOGIKA SIMPAN PELANGGARAN (FOTO & DATABASE)
    if ada_sampah:
        # Cek cooldown agar tidak menyimpan ratusan foto untuk 1 pelanggaran
        if frames_passed - last_detect_frame > cooldown_frames:
            last_detect_frame = frames_passed

            waktu = datetime.datetime.now()
            waktu_str = waktu.strftime("%Y-%m-%d %H:%M:%S")
            img_filename = waktu.strftime("%Y-%m-%d_%H-%M-%S") + ".jpg"

            # Path simpan foto
            save_img_path = f"{SAVE_IMG_DIR}/{img_filename}"
            db_image_path = f"images/detections/{img_filename}"

            cv2.imwrite(save_img_path, annotated_frame) # Simpan foto BERSERTA kotaknya

            # Logika Level Pelanggaran Baru (Rendah/Sedang/Tinggi)
            if max_sampah_conf >= 0.75:
                status_indikasi = "Indikasi Pelanggaran Tinggi"
            elif max_sampah_conf >= 0.50:
                status_indikasi = "Indikasi Pelanggaran Sedang"
            else:
                status_indikasi = "Indikasi Pelanggaran Rendah"

            lokasi = sys.argv[2] if len(sys.argv) > 2 else "Tidak Diketahui"
            objek_terdeteksi = "Manusia dan Sampah"

            try:
                # Kolom confidence_score ditambahin biar masuk ke database
                sql = """INSERT INTO detections 
                         (gambar_bukti, jenis_bukti, status_indikasi, confidence_score, lokasi, waktu_kejadian, created_at, updated_at) 
                         VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"""
                val = (db_image_path, objek_terdeteksi, status_indikasi, max_sampah_conf, lokasi, waktu, waktu, waktu)
                cursor.execute(sql, val)
                db.commit()
                print(f"✅ Pelanggaran ({status_indikasi} - {max_sampah_conf:.2f}) disimpan ke DB pada frame {frames_passed}")
            except Exception as e:
                print(f"❌ Database Error: {e}")

    # Simpan frame ber-kotak ke dalam video output
    out.write(annotated_frame)

    # (Opsional) Tampilkan visual jika script dijalankan manual di Mac
    cv2.imshow("Processing Video", cv2.resize(annotated_frame, (854, 480)))
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Clean up
cap.release()
out.release()
cv2.destroyAllWindows()
cursor.close()
db.close()

print(f"🎉 Selesai! Video hasil AI disimpan di: {OUTPUT_VIDEO_PATH}")