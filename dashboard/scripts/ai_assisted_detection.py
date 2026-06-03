import json
import os
import sys

def detect_with_yolo(file_path: str) -> dict:
    from ultralytics import YOLO
    import cv2

    # Pake model best.pt lu
    model_path = "/Users/karim/Tugas/Laravel/pendeteksi-buang-sampah-sembarangan/dashboard/app/Models/best.pt"
    model = YOLO(model_path)
    model_version = "Custom YOLOv8 (All Classes)"

    ext = os.path.splitext(file_path)[1].lower()
    image_ext = {".jpg", ".jpeg", ".png"}

    output_violations = []

    if ext in image_ext:
        img = cv2.imread(file_path)
        if img is None:
            return {"status": "error", "message": "Gagal memuat gambar"}

        # Confidence diturunin ke 10% (0.10) biar yang blur/jauh tetep ketangkep
        results = model(img, verbose=False, conf=0.10)
        
        ada_pelanggaran = False
        best_conf = 0.0
        kategori_terdeteksi = "Aktivitas Mencurigakan"
        
        for result in results:
            if result.boxes:
                for box in result.boxes:
                    conf = float(box.conf[0])
                    cls = int(box.cls[0])
                    class_name = model.names[cls]
                    
                    # FILTER DIHAPUS TOTAL! Apapun yang dideteksi best.pt langsung masuk
                    ada_pelanggaran = True
                    if conf > best_conf:
                        best_conf = conf
                        # Simpan nama aslinya sesuai yang ada di best.pt lu
                        kategori_terdeteksi = class_name

        if ada_pelanggaran:
            final_conf = best_conf
            if final_conf >= 0.75:
                status_indikasi = "Indikasi Pelanggaran Tinggi"
            elif final_conf >= 0.50:
                status_indikasi = "Indikasi Pelanggaran Sedang"
            else:
                status_indikasi = "Indikasi Pelanggaran Rendah"
                
            base, _ = os.path.splitext(file_path)
            out_path = f"{base}_frame_1.jpg"
            
            # Gambar kotak deteksinya (Bounding Box)
            annotated_img = results[0].plot()
            cv2.imwrite(out_path, annotated_img)
            
            output_violations.append({
                "kategori": kategori_terdeteksi.capitalize(),
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