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

    output_violations = []

    if ext in image_ext:
        results = model(file_path, verbose=False, conf=0.15)
        frame_has_person = False
        frame_trash_conf = 0.0
        frame_cats = set()
        
        for result in results:
            for box in result.boxes:
                cls = int(box.cls[0])
                conf = float(box.conf[0])
                if cls == 0:
                    frame_has_person = True
                elif cls in [24, 26, 39, 41]:
                    if conf > frame_trash_conf: frame_trash_conf = conf
                    if cls == 39: frame_cats.add('Botol')
                    elif cls in [24, 26]: frame_cats.add('Tas/Kantong Plastik')
                    elif cls == 41: frame_cats.add('Gelas/Plastik')
        
        if frame_has_person and frame_trash_conf > 0:
            base, _ = os.path.splitext(file_path)
            out_path = f"{base}_frame_1.jpg"
            cv2.imwrite(out_path, results[0].plot())
            kategori_str = ", ".join(frame_cats) if frame_cats else "Tidak Diketahui"
            output_violations.append({
                "kategori": kategori_str,
                "confidence_score": round(frame_trash_conf, 2),
                "frame_out_path": out_path
            })

    elif ext in video_ext:
        cap = cv2.VideoCapture(file_path)
        frame_index = 0
        
        violations_list = []
        in_event = False
        event_best_conf = 0.0
        event_best_frame = None
        event_categories = set()
        no_violation_frames = 0
        
        while True:
            ret, frame = cap.read()
            if not ret:
                break

            frame_index += 1
            if frame_index % 10 != 0:
                continue

            results = model(frame, verbose=False, conf=0.15)
            
            frame_has_person = False
            frame_trash_conf = 0.0
            frame_cats = set()
            
            for result in results:
                for box in result.boxes:
                    cls = int(box.cls[0])
                    conf = float(box.conf[0])
                    if cls == 0:
                        frame_has_person = True
                    elif cls in [24, 26, 39, 41]:
                        if conf > frame_trash_conf:
                            frame_trash_conf = conf
                        if cls == 39:
                            frame_cats.add('Botol')
                        elif cls in [24, 26]:
                            frame_cats.add('Tas/Kantong Plastik')
                        elif cls == 41:
                            frame_cats.add('Gelas/Plastik')

            if frame_has_person and frame_trash_conf > 0:
                in_event = True
                no_violation_frames = 0
                event_categories.update(frame_cats)
                if frame_trash_conf > event_best_conf:
                    event_best_conf = frame_trash_conf
                    event_best_frame = results[0].plot()
            else:
                if in_event:
                    no_violation_frames += 1
                    # Jika selama 15 iterasi (5 detik) tidak ada pelanggaran beruntun, event berakhir
                    if no_violation_frames >= 15:
                        violations_list.append({
                            "best_frame": event_best_frame,
                            "confidence": event_best_conf,
                            "categories": list(event_categories)
                        })
                        in_event = False
                        event_best_conf = 0.0
                        event_best_frame = None
                        event_categories = set()
                        no_violation_frames = 0

        cap.release()
        
        if in_event and event_best_frame is not None:
            violations_list.append({
                "best_frame": event_best_frame,
                "confidence": event_best_conf,
                "categories": list(event_categories)
            })

        base, _ = os.path.splitext(file_path)
        for i, v in enumerate(violations_list):
            out_path = f"{base}_frame_{i+1}.jpg"
            cv2.imwrite(out_path, v["best_frame"])
            kategori_str = ", ".join(v["categories"]) if v["categories"] else "Tidak Diketahui"
            output_violations.append({
                "kategori": kategori_str,
                "confidence_score": round(v["confidence"], 2),
                "frame_out_path": out_path
            })
    else:
        return {"status": "error", "message": "Format file tidak didukung"}

    return {
        "status": "success",
        "violations": output_violations
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
