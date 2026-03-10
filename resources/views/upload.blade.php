<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Face Detection</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f5f4f0;
            color: #0a0a0f;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 80% 10%, rgba(255,77,46,0.07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 10% 80%, rgba(255,140,66,0.06) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            position: relative;
            z-index: 1;
            max-width: 560px;
            margin: 0 auto;
            padding: 3rem 1.5rem 4rem;
        }

        /* Header */
        .header {
            margin-bottom: 3rem;
            animation: fadeDown 0.7s ease both;
        }

        .header-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Syne', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #ff4d2e;
            background: rgba(255,77,46,0.08);
            border: 1px solid rgba(255,77,46,0.2);
            padding: 0.35rem 0.8rem;
            border-radius: 100px;
            margin-bottom: 1rem;
        }

        .header-eyebrow::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ff4d2e;
            animation: pulse 1.8s ease infinite;
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2.4rem, 7vw, 3.2rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.03em;
            color: #0a0a0f;
        }

        h1 span { color: #ff4d2e; }

        .header-sub {
            margin-top: 0.8rem;
            color: rgba(10,10,15,0.5);
            font-size: 1rem;
            font-weight: 300;
            line-height: 1.6;
        }

        /* Card */
        .card {
            background: #ffffff;
            border: 1px solid rgba(10,10,15,0.1);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 0 rgba(10,10,15,0.06), 0 20px 40px -10px rgba(10,10,15,0.06);
            animation: fadeUp 0.6s ease both;
        }

        .card:nth-child(2) { animation-delay: 0.08s; }
        .card:nth-child(3) { animation-delay: 0.16s; }

        .card-label {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: 'Syne', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(10,10,15,0.4);
            margin-bottom: 1.4rem;
        }

        .card-label .dot {
            width: 18px;
            height: 18px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
        }

        .dot-upload { background: rgba(255,77,46,0.12); color: #ff4d2e; }
        .dot-cam    { background: rgba(0,193,124,0.12); color: #00c17c; }

        /* Upload zone */
        .upload-zone {
            border: 1.5px dashed rgba(10,10,15,0.15);
            border-radius: 14px;
            padding: 2.4rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #f5f4f0;
            position: relative;
            overflow: hidden;
        }

        .upload-zone:hover {
            border-color: #ff4d2e;
            background: rgba(255,77,46,0.03);
        }

        .upload-zone:hover .upload-icon-wrap {
            transform: scale(1.08) translateY(-2px);
        }

        .upload-icon-wrap {
            width: 56px;
            height: 56px;
            background: rgba(255,77,46,0.09);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            transition: transform 0.25s ease;
            color: #ff4d2e;
            font-size: 1.4rem;
        }

        .upload-title {
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            color: #0a0a0f;
            margin-bottom: 0.3rem;
        }

        .upload-hint {
            font-size: 0.82rem;
            color: rgba(10,10,15,0.4);
            font-weight: 300;
        }

        /* Preview */
        #preview {
            width: 100%;
            border-radius: 12px;
            margin-top: 1.2rem;
            display: none;
            box-shadow: 0 4px 20px rgba(10,10,15,0.08);
            object-fit: cover;
            max-height: 260px;
        }

        /* Webcam */
        video {
            width: 100%;
            border-radius: 12px;
            background: #1a1a2e;
            display: block;
            aspect-ratio: 16/10;
            object-fit: cover;
        }

        .webcam-overlay {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
        }

        .webcam-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(6px);
            border-radius: 100px;
            padding: 0.25rem 0.7rem;
            font-size: 0.7rem;
            font-weight: 500;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .webcam-badge::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #00c17c;
            animation: pulse 1.4s ease infinite;
        }

        /* Buttons */
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            width: 100%;
            padding: 0.95rem 1.5rem;
            border-radius: 12px;
            border: none;
            font-family: 'Syne', sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            cursor: pointer;
            transition: all 0.22s ease;
            margin-top: 1rem;
        }

        .btn-primary {
            background: #0a0a0f;
            color: #ffffff;
            box-shadow: 0 1px 0 rgba(255,255,255,0.1) inset, 0 6px 18px rgba(10,10,15,0.18);
        }

        .btn-primary:hover:not(:disabled) {
            background: #ff4d2e;
            box-shadow: 0 8px 24px rgba(255,77,46,0.25);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(0,193,124,0.1);
            color: #007a50;
            border: 1px solid rgba(0,193,124,0.25);
        }

        .btn-secondary:hover:not(:disabled) {
            background: rgba(0,193,124,0.18);
            transform: translateY(-1px);
        }

        .btn:disabled {
            opacity: 0.38;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Messages */
        .message {
            padding: 0.9rem 1.1rem;
            border-radius: 10px;
            margin-top: 1rem;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
        }

        .error   { background: rgba(239,68,68,0.08); color: #b91c1c; border: 1px solid rgba(239,68,68,0.15); }
        .success { background: rgba(0,193,124,0.08); color: #065f46; border: 1px solid rgba(0,193,124,0.2); }

        /* Result */
        .result-card {
            background: #0a0a0f;
            color: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            margin-top: 1.2rem;
            animation: fadeUp 0.5s ease both;
            box-shadow: 0 20px 50px rgba(10,10,15,0.2);
        }

        .result-label {
            font-family: 'Syne', sans-serif;
            font-size: 0.68rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 0.6rem;
        }

        .result-count {
            font-family: 'Syne', sans-serif;
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1;
            color: #ff4d2e;
            margin-bottom: 0.2rem;
        }

        .result-unit {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.55);
            font-weight: 300;
            margin-bottom: 1.4rem;
        }

        .result-image {
            width: 100%;
            border-radius: 12px;
            display: block;
            box-shadow: 0 8px 30px rgba(0,0,0,0.35);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: rgba(10,10,15,0.25);
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin: 0.2rem 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(10,10,15,0.1);
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.55; transform: scale(0.85); }
        }

        @media (max-width: 480px) {
            .wrapper { padding: 2rem 1rem 3rem; }
            .card    { padding: 1.5rem; }
            h1       { font-size: 2.1rem; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <header class="header">
        <div class="header-eyebrow">AI Powered</div>
        <h1>Face<br><span>Detection</span></h1>
        <p class="header-sub">Phát hiện khuôn mặt từ ảnh tải lên<br>hoặc qua webcam theo thời gian thực.</p>
    </header>

    <!-- Upload -->
    <div class="card">
        <div class="card-label">
            <span class="dot dot-upload"><i class="fas fa-image"></i></span>
            Tải ảnh lên
        </div>

        <form action="/detect" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="upload-zone" id="uploadArea" onclick="document.getElementById('fileInput').click()">
                <div class="upload-icon-wrap"><i class="fas fa-cloud-upload-alt"></i></div>
                <div class="upload-title">Chọn hoặc kéo thả ảnh</div>
                <div class="upload-hint">JPG, PNG, WEBP — tối đa 10MB</div>
            </div>
            <input type="file" name="image" id="fileInput" accept="image/*" required hidden>
        </form>

        <img id="preview" alt="Xem trước ảnh">

        <button type="submit" form="uploadForm" class="btn btn-primary" id="uploadBtn" disabled>
            <i class="fas fa-magnifying-glass"></i> Phân tích ảnh
        </button>
    </div>

    <div class="divider">hoặc</div>

    <!-- Webcam -->
    <div class="card">
        <div class="card-label">
            <span class="dot dot-cam"><i class="fas fa-video"></i></span>
            Webcam trực tiếp
        </div>

        <div class="webcam-overlay">
            <video id="video" autoplay playsinline muted></video>
            <div class="webcam-badge">LIVE</div>
        </div>
        <canvas id="canvas" style="display:none;"></canvas>

        <button class="btn btn-secondary" id="captureBtn">
            <i class="fas fa-camera"></i> Chụp & Phát hiện
        </button>
    </div>

    @if(isset($faces))
        <div class="result-card">
            <div class="result-label">Kết quả phát hiện</div>
            <div class="result-count">{{ $faces }}</div>
            <div class="result-unit">khuôn mặt được phát hiện</div>
            @if(isset($image))
                <img class="result-image" src="{{ $image }}" alt="Kết quả">
            @endif
        </div>
    @endif

</div>

<script>
    const fileInput  = document.getElementById('fileInput');
    const preview    = document.getElementById('preview');
    const uploadBtn  = document.getElementById('uploadBtn');
    const captureBtn = document.getElementById('captureBtn');
    const video      = document.getElementById('video');
    const canvas     = document.getElementById('canvas');
    const uploadArea = document.getElementById('uploadArea');

    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.style.borderColor = '#ff4d2e'; });
    uploadArea.addEventListener('dragleave', () => { uploadArea.style.borderColor = ''; });
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.style.borderColor = '';
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            fileInput.files = e.dataTransfer.files;
            showPreview(file);
        }
    });

    fileInput.onchange = e => { if (e.target.files[0]) showPreview(e.target.files[0]); };

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = () => {
            preview.src = reader.result;
            preview.style.display = 'block';
            uploadBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    }

    let stream = null;

    async function startWebcam() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
            video.srcObject = stream;
        } catch (err) {
            const msg = document.createElement('div');
            msg.className = 'message error';
            msg.innerHTML = '<i class="fas fa-triangle-exclamation"></i> Không thể mở webcam. Vui lòng cho phép quyền truy cập.';
            captureBtn.parentNode.insertBefore(msg, captureBtn.nextSibling);
            captureBtn.disabled = true;
        }
    }

    startWebcam();

    window.onbeforeunload = () => { if (stream) stream.getTracks().forEach(t => t.stop()); };

    function sendImage(blob, filename) {
        const formData = new FormData();
        formData.append("image", blob, filename);
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        captureBtn.disabled = true;
        captureBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

        fetch("/detect", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "X-Requested-With": "XMLHttpRequest"
            },
            credentials: "same-origin",
            body: formData
        })
            .then(res => { if (!res.ok) throw new Error(); return res.text(); })
            .then(html => { document.open(); document.write(html); document.close(); })
            .catch(() => {
                const msg = document.createElement('div');
                msg.className = 'message error';
                msg.innerHTML = '<i class="fas fa-triangle-exclamation"></i> Có lỗi xảy ra. Vui lòng thử lại.';
                document.querySelector('.wrapper').appendChild(msg);
                captureBtn.disabled = false;
                captureBtn.innerHTML = '<i class="fas fa-camera"></i> Chụp & Phát hiện';
            });
    }

    captureBtn.onclick = () => {
        if (!video.srcObject) return;
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        canvas.toBlob(blob => { if (blob) sendImage(blob, "webcam_capture.jpg"); }, 'image/jpeg', 0.92);
    };
</script>

</body>
</html>
