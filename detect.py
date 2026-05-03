import cv2
from ultralytics import YOLO
import datetime
import mysql.connector
import requests
import os
import time

# ================= CONFIG =================
TELEGRAM_TOKEN = "8668693014:AAHvpYOa2GLSPMviS0dxUe7k-OgLDXl9oyE"
CHAT_ID = "8624001484"

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="cctv_ai"
)

cursor = db.cursor()

model = YOLO("yolov8n.pt")

if not os.path.exists("images"):
    os.makedirs("images")

last_detect_time = 0
delay = 10  # detik

# ==========================================

def kirim_telegram(pesan, foto_path=None):
    try:
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
        break

    results = model(frame)

    ada_manusia = False
    ada_sampah = False

    for r in results:
        for box in r.boxes:
            cls = int(box.cls[0])

            # 0 = person
            if cls == 0:
                ada_manusia = True

            # 39 = bottle, 41 = cup
            if cls in [39, 41]:
                ada_sampah = True

    # 🔥 LOGIKA DETEKSI
    if ada_manusia and ada_sampah:
        now = time.time()

        if now - last_detect_time > delay:
            last_detect_time = now

            waktu = datetime.datetime.now()
            waktu_str = waktu.strftime("%Y-%m-%d_%H-%M-%S")

            filename = f"images/deteksi_{waktu_str}.jpg"

            cv2.imwrite(filename, frame)

            print("Kemungkinan buang sampah!")

            cursor.execute(
                "INSERT INTO events (waktu, gambar) VALUES (%s, %s)",
                (waktu, filename)
            )
            db.commit()

            kirim_telegram(
                f"⚠️ Kemungkinan buang sampah terdeteksi!\nWaktu: {waktu}",
                filename
            )

    cv2.imshow("AI CCTV", frame)

    if cv2.waitKey(1) == 27:
        break

cap.release()
cv2.destroyAllWindows()