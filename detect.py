import cv2
from ultralytics import YOLO
import datetime
import mysql.connector
import os
import time

# ================= CONFIG =================
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="pendeteksi_db" 
)

cursor = db.cursor()

# Pakai model standar biar "benda dicurigai" kedeteksi (buat simulasi)
model = YOLO("yolov8n.pt")

# Simpan ke folder public Laravel
SAVE_DIR = "dashboard/public/images/detections"
os.makedirs(SAVE_DIR, exist_ok=True)

last_detect_time = 0
delay = 20  # Jeda 20 detik
# ==========================================

def buat_link_wa(waktu, lokasi):
    nomor_rt = "6285697256961" 
    pesan = f"🚨 *LAPORAN CCTV SAMPAH* 🚨\n\nHalo Pak RT, terdeteksi warga buang sampah sembarangan.\n\nWaktu: {waktu}\nLokasi: {lokasi}\nSanksi: *Kerja Bakti Bersih Sungai*."
    
    pesan_format = pesan.replace(' ', '%20').replace('\n', '%0A')
    link = f"https://wa.me/{nomor_rt}?text={pesan_format}"
    
    return link

cap = cv2.VideoCapture(0) 

print("🚀 AI Running... Tekan 'q' untuk berhenti.")

while True:
    ret, frame = cap.read()
    if not ret:
        print("Kamera tidak terbaca.")
        break

    # Deteksi dengan threshold rendah (0.2) biar lebih sensitif
    results = model.track(frame, persist=True, verbose=False, conf=0.2)

    ada_manusia = False
    ada_sampah = False

    # LOOP UTAMA DETEKSI
    for r in results:
        if r.boxes:
            for box in r.boxes:
                cls = int(box.cls[0])
                class_name = model.names[cls]

                # 1. Cek Manusia
                if class_name == "person":
                    ada_manusia = True
                
                # 2. Cek Sampah (Asli atau Simulasi)
                # Tambahkan 'trash' kalau nanti lo balik pakai model best.pt
                benda_dicurigai = ["cell phone", "cup", "bottle", "box", "suitcase", "trash"]
                if class_name in benda_dicurigai:
                    ada_sampah = True

    # LOGIKA SIMPAN KE DATABASE
    if ada_manusia and ada_sampah:
        now = time.time()

        if now - last_detect_time > delay:
            last_detect_time = now

            waktu = datetime.datetime.now()
            waktu_str = waktu.strftime("%Y-%m-%d %H:%M:%S")
            file_name = waktu.strftime("%Y-%m-%d_%H-%M-%S") + ".jpg"

            save_path = f"{SAVE_DIR}/{file_name}"
            db_image_path = f"images/detections/{file_name}"

            # Simpan Foto
            cv2.imwrite(save_path, frame)

            status_indikasi = "Indikasi Pelanggaran Tinggi"
            lokasi = "Sungai Sukapura"
            objek_terdeteksi = "Manusia dan Benda Mencurigakan"

            try:
                sql = """INSERT INTO detections 
                         (gambar_bukti, jenis_bukti, status_indikasi, lokasi, waktu_kejadian, created_at, updated_at) 
                         VALUES (%s, %s, %s, %s, %s, %s, %s)"""
                val = (db_image_path, objek_terdeteksi, status_indikasi, lokasi, waktu, waktu, waktu)
                cursor.execute(sql, val)
                db.commit()

                print(f"\n✅ PELANGGARAN TERCATAT: {waktu_str}")
                print(f"🔗 Link WA Admin: {buat_link_wa(waktu_str, lokasi)}")
                
            except Exception as e:
                print(f"❌ Database Error: {e}")

    # Preview Window
    annotated_frame = results[0].plot()
    cv2.imshow("SiCCTV Sampah - Monitoring", annotated_frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
cursor.close()
db.close()