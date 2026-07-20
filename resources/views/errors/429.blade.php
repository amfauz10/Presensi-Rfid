<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terlalu Banyak Percobaan - SDN Tengah 03</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .box {
            max-width: 420px;
            width: 100%;
            text-align: center;
            padding: 2.5rem 2rem;
        }
        .icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: #fef3c7;
            color: #b45309;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.25rem;
        }
        h3 {
            color: #0f172a;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        p {
            color: #64748b;
            font-size: 0.92rem;
        }
        .btn-back {
            border-radius: 10px;
            padding: 10px 24px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="icon-wrap">
            <i class="bi bi-shield-lock"></i>
        </div>
        <h3 class="mb-2">Terlalu Banyak Percobaan Login</h3>
        <p class="mb-4">
            Untuk keamanan akun, percobaan login sementara dibatasi.
            Silakan tunggu sebentar (sekitar 1 menit) lalu coba lagi.
        </p>
        <a href="{{ url('/') }}" class="btn btn-primary btn-back">Kembali ke Halaman Login</a>
    </div>
</body>
</html>