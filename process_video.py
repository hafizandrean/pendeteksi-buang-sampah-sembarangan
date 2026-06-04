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
    database="pendeteksi_db" 
)
cursor = db.cursor()

# ================= CONFIG AI & FOLDER =================
model = YOLO("/Users/karim/Tugas/Laravel/pendeteksi-buang-sampah-sembarangan/dashboard/app/Models/best.pt")

# Path input video
if len(sys.argv) > 1:
    INPUT_VIDEO_PATH = sys.argv[1]
else:
    print("Error: Path video tidak dikirim!")
    exit()

# Folder penyimpanan
SAVE_IMG_DIR = "/Users/karim/Tugas/Laravel/pendeteksi-buang-sampah-sembarangan/dashboard/public/images/detections"
SAVE_VID_DIR = "/Users/karim/Tugas/Laravel/pendeteksi-buang-sampah-sembarangan/dashboard/public/videos/hasil_ai"
os.makedirs(SAVE_IMG_DIR, exist_ok=True)
os.makedirs(SAVE_VID_DIR, exist_ok=True)

# Setup Pembacaan Video
cap = cv2.VideoCapture(INPUT_VIDEO_PATH)
if not cap.isOpened():
    print("Error: Video tidak dapat dibuka!")
    exit()

# Setup Penulisan Video
width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
fps = int(cap.get(cv2.CAP_PROP_FPS))
if fps == 0: fps = 30 # Jaga-jaga kalau OpenCV gagal baca FPS video

output_filename = f"AI_Terdeteksi_{datetime.datetime.now().strftime('%Y%m%d_%H%M%S')}.mp4"
OUTPUT_VIDEO_PATH = f"{SAVE_VID_DIR}/{output_filename}"

# Output video
out = cv2.VideoWriter(OUTPUT_VIDEO_PATH, cv2.VideoWriter_fourcc(*'mp4v'), int(fps/5), (width, height))

frames_passed = 0
cooldown_frames = fps * 15 
last_detect_frame = -cooldown_frames

print("Memproses video di background...")

# ================= LOOPING PROSES VIDEO =================
while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break 
    
    frames_passed += 1

    # Skip manual: Cuma proses 1 dari tiap 5 frame
    if frames_passed % 5 != 0:
        continue

    # Conf disamakan dengan detect.py (0.2) biar lebih gampang nangkep sampah
    results = model.track(frame, persist=True, verbose=False, conf=0.2, imgsz=320)
    
    ada_sampah = False
    max_sampah_conf = 0.0 

    for r in results:
        if r.boxes:
            for box in r.boxes:
                conf = float(box.conf[0])
                # Karena lu pake best.pt (model custom lu), APAPUN yg kena kotak berarti sampah
                ada_sampah = True
                if conf > max_sampah_conf:
                    max_sampah_conf = conf 

    annotated_frame = results[0].plot()

    # LOGIKA SIMPAN PELANGGARAN (FOTO & DATABASE)
    if ada_sampah:
        if frames_passed - last_detect_frame > cooldown_frames:
            last_detect_frame = frames_passed

            waktu = datetime.datetime.now()
            img_filename = waktu.strftime("%Y-%m-%d_%H-%M-%S") + ".jpg"

            save_img_path = f"{SAVE_IMG_DIR}/{img_filename}"
            db_image_path = f"images/detections/{img_filename}"

            cv2.imwrite(save_img_path, annotated_frame) 

            if max_sampah_conf >= 0.75:
                status_indikasi = "Indikasi Pelanggaran Tinggi"
            elif max_sampah_conf >= 0.50:
                status_indikasi = "Indikasi Pelanggaran Sedang"
            else:
                status_indikasi = "Indikasi Pelanggaran Rendah"

            lokasi = sys.argv[2] if len(sys.argv) > 2 else "Tidak Diketahui"
            objek_terdeteksi = "Manusia dan Sampah"

            try:
                sql = """INSERT INTO detections 
                         (gambar_bukti, jenis_bukti, status_indikasi, confidence_score, lokasi, waktu_kejadian, created_at, updated_at) 
                         VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"""
                val = (db_image_path, objek_terdeteksi, status_indikasi, max_sampah_conf, lokasi, waktu, waktu, waktu)
                cursor.execute(sql, val)
                db.commit()
                print(f"Pelanggaran disimpan ke DB: {max_sampah_conf:.2f}")
            except Exception as e:
                print(f"Database Error: {e}")

    # Simpan frame ke video output
    out.write(annotated_frame)

cap.release()
out.release()
cursor.close()
db.close()

print("Proses selesai dan video tersimpan.")