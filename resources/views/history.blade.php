<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Detection History</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

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

        .wrapper {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 1.5rem 5rem;
        }

        /* Header */
        .header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2.5rem;
            animation: fadeDown 0.6s ease both;
            flex-wrap: wrap;
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
            padding: 0.3rem 0.75rem;
            border-radius: 100px;
            margin-bottom: 0.8rem;
        }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 5vw, 2.8rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.03em;
        }

        h1 span { color: #ff4d2e; }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Syne', sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            color: #0a0a0f;
            background: #ffffff;
            border: 1px solid rgba(10,10,15,0.1);
            padding: 0.6rem 1.1rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(10,10,15,0.06);
            white-space: nowrap;
        }

        .back-btn:hover {
            background: #ff4d2e;
            color: #ffffff;
            border-color: #ff4d2e;
            transform: translateY(-1px);
        }

        /* Stats bar */
        .stats-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            animation: fadeUp 0.5s ease 0.05s both;
            flex-wrap: wrap;
        }

        .stat-chip {
            background: #ffffff;
            border: 1px solid rgba(10,10,15,0.1);
            border-radius: 12px;
            padding: 0.8rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            box-shadow: 0 2px 8px rgba(10,10,15,0.04);
        }

        .stat-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
        }

        .stat-icon-orange { background: rgba(255,77,46,0.1); color: #ff4d2e; }
        .stat-icon-green  { background: rgba(0,193,124,0.1);  color: #00c17c; }

        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #0a0a0f;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.72rem;
            color: rgba(10,10,15,0.45);
            margin-top: 0.2rem;
        }

        /* Empty state */
        .empty-state {
            background: #ffffff;
            border: 1px solid rgba(10,10,15,0.08);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
            animation: fadeUp 0.5s ease both;
        }

        .empty-icon {
            width: 64px; height: 64px;
            background: rgba(255,77,46,0.08);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.2rem;
            font-size: 1.6rem;
            color: #ff4d2e;
        }

        .empty-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #0a0a0f;
            margin-bottom: 0.4rem;
        }

        .empty-sub {
            font-size: 0.88rem;
            color: rgba(10,10,15,0.4);
            font-weight: 300;
        }

        /* Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.2rem;
        }

        .history-card {
            background: #ffffff;
            border: 1px solid rgba(10,10,15,0.08);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 0 rgba(10,10,15,0.04), 0 10px 30px -8px rgba(10,10,15,0.07);
            transition: all 0.25s ease;
            animation: fadeUp 0.5s ease both;
        }

        .history-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 2px 0 rgba(10,10,15,0.06), 0 20px 40px -10px rgba(10,10,15,0.13);
        }

        .card-thumb {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: #1a1a2e;
        }

        .card-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .history-card:hover .card-thumb img {
            transform: scale(1.04);
        }

        .face-badge {
            position: absolute;
            top: 10px; right: 10px;
            background: #0a0a0f;
            color: #ffffff;
            font-family: 'Syne', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .face-badge i { color: #ff4d2e; font-size: 0.7rem; }

        .card-body {
            padding: 1rem 1.1rem 1.1rem;
        }

        .card-time {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            color: rgba(10,10,15,0.4);
            font-weight: 300;
        }

        .card-time i { font-size: 0.7rem; }

        .card-faces {
            font-family: 'Syne', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #0a0a0f;
            margin-top: 0.35rem;
        }

        .card-faces span { color: #ff4d2e; }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .wrapper { padding: 2rem 1rem 3rem; }
            .grid { grid-template-columns: 1fr 1fr; gap: 0.8rem; }
        }

        @media (max-width: 360px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <div class="header">
        <div>
            <div class="header-eyebrow">AI Powered</div>
            <h1>Detection<br><span>History</span></h1>
        </div>
        <a href="/" class="back-btn">
            <i class="fas fa-arrow-left"></i> Phát hiện mới
        </a>
    </div>

    @if($faces->count())

        <div class="stats-bar">
            <div class="stat-chip">
                <div class="stat-icon stat-icon-orange"><i class="fas fa-clock-rotate-left"></i></div>
                <div>
                    <div class="stat-value">{{ $faces->count() }}</div>
                    <div class="stat-label">Lần phát hiện</div>
                </div>
            </div>
            <div class="stat-chip">
                <div class="stat-icon stat-icon-green"><i class="fas fa-face-smile"></i></div>
                <div>
                    <div class="stat-value">{{ $faces->sum('faces_detected') }}</div>
                    <div class="stat-label">Tổng khuôn mặt</div>
                </div>
            </div>
        </div>

        <div class="grid">
            @foreach($faces as $index => $face)
                <div class="history-card" style="animation-delay: {{ $index * 0.05 }}s">
                    <div class="card-thumb">
                        <img src="{{ $face->image }}" alt="Ảnh phát hiện">
                        <div class="face-badge">
                            <i class="fas fa-user"></i>
                            {{ $face->faces_detected }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-time">
                            <i class="fas fa-clock"></i>
                            {{ $face->created_at->diffForHumans() }}
                        </div>
                        <div class="card-faces">
                            Phát hiện <span>{{ $face->faces_detected }}</span> khuôn mặt
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else

        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-photo-film"></i></div>
            <div class="empty-title">Chưa có lịch sử nào</div>
            <div class="empty-sub">Hãy phát hiện khuôn mặt để xem lịch sử tại đây.</div>
        </div>

    @endif

</div>

</body>
</html>
