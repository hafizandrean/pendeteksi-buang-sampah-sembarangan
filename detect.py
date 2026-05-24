import cv2
from ultralytics import YOLO
import datetime
import mysql.connector
import requests
import os
import time

# ================= CONFIG =================
TELEGRAM_TOKEN = "ISI_TOKEN_KAMU"
CHAT_ID = "ISI_CHAT_ID_KAMU"

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="pendeteksi_db"
)

cursor = db.cursor()

model = YOLO("model/best.pt")

# Simpan gambar ke folder public Laravel supaya bisa tampil di browser
SAVE_DIR = "dashboard/public/images"
os.makedirs(SAVE_DIR, exist_ok=True)

last_detect_time = 0
delay = 10

# ==========================================

def kirim_telegram(pesan, foto_path=None):
    try:
        if TELEGRAM_TOKEN and CHAT_ID:
            requests.post(
                f"https://api.telegram.org/bot{TELEGRAM_TOKEN}/sendMessage",
                data={"chat_id": CHAT_ID, "text": pesan}
            )

            if foto_path:
                with open(foto_path, "rb") as foto:
                    requests.post(
                        f"https://api.telegram.org/bot{TELEGRAM_TOKEN}/sendPhoto",
                        data={"chat_id": CHAT_ID},
                        files={"photo": foto}
                    )
    except Exception as e:
        print("Gagal kirim Telegram:", e)


cap = cv2.VideoCapture(0)

while True:
    ret, frame = cap.read()

    if not ret:
        print("Kamera tidak terbaca.")
        break

    results = model(frame)

    ada_manusia = False
    ada_sampah = False

    for r in results:
        for box in r.boxes:
            cls = int(box.cls[0])
            class_name = model.names[cls]

            if class_name == "person":
                ada_manusia = True

            if class_name == "trash":
                ada_sampah = True

    if ada_manusia and ada_sampah:
        now = time.time()

        if now - last_detect_time > delay:
            last_detect_time = now

            waktu = datetime.datetime.now()
            waktu_str = waktu.strftime("%Y-%m-%d_%H-%M-%S")

            image_name = f"deteksi_{waktu_str}.jpg"

            save_path = f"{SAVE_DIR}/{image_name}"
            db_image_path = f"images/{image_name}"

            cv2.imwrite(save_path, frame)

            status_indikasi = "Indikasi Pelanggaran Tinggi"
            lokasi = "Area Sungai"
            objek_terdeteksi = "Manusia dan objek sampah"

            print("Kemungkinan buang sampah terdeteksi!")

            cursor.execute(
                """
                INSERT INTO detections 
                (image_path, detected_objects, status_indikasi, location, detected_at, created_at, updated_at)
                VALUES (%s, %s, %s, %s, %s, %s, %s)
                """,
                (
                    db_image_path,
                    objek_terdeteksi,
                    status_indikasi,
                    lokasi,
                    waktu,
                    waktu,
                    waktu
                )
            )

            db.commit()

            kirim_telegram(
                f"⚠️ Kemungkinan buang sampah terdeteksi!\nWaktu: {waktu}\nLokasi: {lokasi}",
                save_path
            )

    annotated_frame = results[0].plot()
    cv2.imshow("AI CCTV", annotated_frame)

    if cv2.waitKey(1) == 27:
        break

cap.release()
cv2.destroyAllWindows()
cursor.close()
db.close()