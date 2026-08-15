import json
import os
import sys

def detect_with_yolo(file_path: str) -> dict:
    from ultralytics import YOLO
    import cv2

    # Gunakan model YOLOv8n default karena fokus hanya pada person
    model = YOLO("yolov8n.pt")
    model_version = "YOLOv8 COCO (Person Only)"

    ext = os.path.splitext(file_path)[1].lower()
    image_ext = {".jpg", ".jpeg", ".png"}
    video_ext = {".mp4", ".mov", ".avi", ".mkv"}

    output_violations = []

    if ext in image_ext:
        # Load image untuk mendapatkan dimensi
        img = cv2.imread(file_path)
        if img is None:
            return {"status": "error", "message": "Gagal memuat gambar"}
        height, width = img.shape[:2]
        river_zone_y = int(height * 0.6) # Asumsi area sungai adalah 40% area bawah gambar

        results = model(img, verbose=False, conf=0.30)
        
        best_person_conf = 0.0
        person_in_zone = False
        
        for result in results:
            for box in result.boxes:
                cls = int(box.cls[0])
                conf = float(box.conf[0])
                
                # Fokus HANYA pada person (class 0)
                if cls == 0 and conf >= 0.30:
                    x1, y1, x2, y2 = map(int, box.xyxy[0])
                    # Gunakan bagian bawah bounding box (kaki) sebagai titik referensi lokasi
                    cy = y2 
                    
                    if cy >= river_zone_y:
                        person_in_zone = True
                        if conf > best_person_conf:
                            best_person_conf = conf

        if person_in_zone:
            final_conf = best_person_conf
            if final_conf >= 0.65:
                status_indikasi = "Indikasi Pelanggaran Tinggi"
            else:
                status_indikasi = "Perlu Validasi"
                
            base, _ = os.path.splitext(file_path)
            out_path = f"{base}_frame_1.jpg"
            # Gambar polygon zona sungai untuk visualisasi
            annotated_img = results[0].plot(labels=False)
            cv2.line(annotated_img, (0, river_zone_y), (width, river_zone_y), (0, 0, 255), 2)
            cv2.putText(annotated_img, "ZONA SUNGAI", (10, river_zone_y + 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)
            
            cv2.imwrite(out_path, annotated_img)
            
            output_violations.append({
                "kategori": "Indikasi Aktivitas Mencurigakan",
                "confidence_score": round(final_conf, 2),
                "frame_out_path": out_path,
                "status_indikasi": status_indikasi
            })

    elif ext in video_ext:
        cap = cv2.VideoCapture(file_path)
        frame_width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
        frame_height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
        fps = cap.get(cv2.CAP_PROP_FPS)
        if fps == 0 or fps != fps: fps = 30.0 # fallback

        river_zone_y = int(frame_height * 0.6)
        frame_index = 0
        
        violations_list = []
        
        # Tracking variabel
        consecutive_frames_in_zone = 0
        event_best_conf = 0.0
        event_best_frame = None
        no_person_frames = 0
        in_event = False
        
        # Proses setiap 10 frame (kurang lebih 3 FPS) untuk optimasi
        FRAME_SKIP = 10 
        
        while True:
            ret, frame = cap.read()
            if not ret:
                break

            frame_index += 1
            if frame_index % FRAME_SKIP != 0:
                continue

            results = model(frame, verbose=False, conf=0.30)
            
            person_in_zone_this_frame = False
            frame_best_conf = 0.0
            
            for result in results:
                for box in result.boxes:
                    cls = int(box.cls[0])
                    conf = float(box.conf[0])
                    
                    if cls == 0 and conf >= 0.30:
                        x1, y1, x2, y2 = map(int, box.xyxy[0])
                        cy = y2
                        
                        if cy >= river_zone_y:
                            person_in_zone_this_frame = True
                            if conf > frame_best_conf:
                                frame_best_conf = conf

            if person_in_zone_this_frame:
                in_event = True
                consecutive_frames_in_zone += 1
                no_person_frames = 0
                
                if frame_best_conf > event_best_conf or event_best_frame is None:
                    event_best_conf = frame_best_conf
                    # Gambar zona
                    annotated = results[0].plot(labels=False)
                    cv2.line(annotated, (0, river_zone_y), (frame_width, river_zone_y), (0, 0, 255), 2)
                    cv2.putText(annotated, "ZONA SUNGAI", (10, river_zone_y + 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)
                    event_best_frame = annotated
            else:
                if in_event:
                    no_person_frames += 1
                    # Jika 15 iterasi (~5 detik) tidak ada orang di zona, akhiri event
                    if no_person_frames >= 15:
                        violations_list.append({
                            "best_frame": event_best_frame,
                            "base_confidence": event_best_conf,
                            "frames_in_zone": consecutive_frames_in_zone
                        })
                        in_event = False
                        consecutive_frames_in_zone = 0
                        event_best_conf = 0.0
                        event_best_frame = None
                        no_person_frames = 0

        cap.release()
        
        if in_event and event_best_frame is not None:
            violations_list.append({
                "best_frame": event_best_frame,
                "base_confidence": event_best_conf,
                "frames_in_zone": consecutive_frames_in_zone
            })

        base, _ = os.path.splitext(file_path)
        for i, v in enumerate(violations_list):
            out_path = f"{base}_frame_{i+1}.jpg"
            cv2.imwrite(out_path, v["best_frame"])
            
            # Kalkulasi confidence final berdasar durasi
            # Asumsi: FRAME_SKIP = 10, fps = 30 -> 1 frame = 0.33 detik.
            # Jika frames_in_zone >= 9 (sekitar 3 detik), tingkatkan confidence
            final_conf = v["base_confidence"]
            if v["frames_in_zone"] >= 9:
                final_conf = min(0.95, final_conf + 0.25)
            elif v["frames_in_zone"] >= 4:
                final_conf = min(0.85, final_conf + 0.15)
                
            if final_conf >= 0.65:
                status_indikasi = "Indikasi Pelanggaran Tinggi"
            else:
                status_indikasi = "Perlu Validasi"

            output_violations.append({
                "kategori": "Indikasi Aktivitas Mencurigakan",
                "confidence_score": round(final_conf, 2),
                "frame_out_path": out_path,
                "status_indikasi": status_indikasi
            })
    else:
        return {"status": "error", "message": "Format file tidak didukung"}

    return {
        "status": "success",
        "violations": output_violations,
        "model_version": model_version
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
