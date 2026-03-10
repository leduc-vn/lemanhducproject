from flask import Flask, request, jsonify
import cv2
import numpy as np
import base64

app = Flask(__name__)

# load model detect face
face_cascade = cv2.CascadeClassifier(
    cv2.data.haarcascades + "haarcascade_frontalface_default.xml"
)

@app.route("/detect", methods=["POST"])
def detect():

    if "image" not in request.files:
        return jsonify({"error": "no image uploaded"}), 400

    file = request.files["image"]

    # đọc file ảnh
    img_bytes = file.read()
    npimg = np.frombuffer(img_bytes, np.uint8)

    img = cv2.imdecode(npimg, cv2.IMREAD_COLOR)

    if img is None:
        return jsonify({"error": "invalid image"}), 400

    # resize ảnh để detect tốt hơn
    img = cv2.resize(img, (640, 480))

    # chuyển sang grayscale
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # detect face
    faces = face_cascade.detectMultiScale(
        gray,
        scaleFactor=1.1,
        minNeighbors=5,
        minSize=(40, 40)
    )

    # vẽ khung mặt
    for (x, y, w, h) in faces:
        cv2.rectangle(img, (x, y), (x+w, y+h), (0, 255, 0), 3)

    # encode ảnh về base64
    _, buffer = cv2.imencode(".jpg", img)
    img_base64 = base64.b64encode(buffer).decode("utf-8")

    return jsonify({
        "faces_detected": len(faces),
        "image": "data:image/jpeg;base64," + img_base64
    })


if __name__ == "__main__":
    app.run(debug=True, port=5000)
