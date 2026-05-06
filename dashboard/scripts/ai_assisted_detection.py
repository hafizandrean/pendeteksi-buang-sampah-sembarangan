import json
import os
import sys


def detect_with_yolo(file_path: str) -> dict:
    from ultralytics import YOLO
    import cv2

    model = YOLO("yolov8n.pt")

    ext = os.path.splitext(file_path)[1].lower()
    image_ext = {".jpg", ".jpeg", ".png"}
    video_ext = {".mp4", ".mov", ".avi", ".mkv"}

    person_detected = False
    trash_detected = False

    def update_flags(results):
        nonlocal person_detected, trash_detected
        for result in results:
            for box in result.boxes:
                cls = int(box.cls[0])
                if cls == 0:
                    person_detected = True
                if cls in [39, 41]:  # bottle, cup
                    trash_detected = True

    if ext in image_ext:
        results = model(file_path)
        update_flags(results)
    elif ext in video_ext:
        cap = cv2.VideoCapture(file_path)
        frame_index = 0
        while True:
            ret, frame = cap.read()
            if not ret:
                break

            frame_index += 1
            if frame_index % 10 != 0:
                continue

            results = model(frame)
            update_flags(results)

            if person_detected and trash_detected:
                break
        cap.release()
    else:
        return {"status": "error", "message": "Format file tidak didukung"}

    violation = person_detected and trash_detected
    return {
        "status": "success",
        "violation": violation,
        "kategori": "Umum",
    }


def main():
    if len(sys.argv) < 2:
        print(json.dumps({"status": "error", "message": "Path file tidak diberikan"}))
        sys.exit(1)

    file_path = sys.argv[1]
    if not os.path.exists(file_path):
        print(json.dumps({"status": "error", "message": "File bukti tidak ditemukan"}))
        sys.exit(1)

    try:
        result = detect_with_yolo(file_path)
        print(json.dumps(result))
        sys.exit(0 if result.get("status") == "success" else 1)
    except Exception as err:
        print(json.dumps({"status": "error", "message": str(err)}))
        sys.exit(1)


if __name__ == "__main__":
    main()
