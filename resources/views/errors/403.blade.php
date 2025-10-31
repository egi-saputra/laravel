{{-- <!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Akses Ditolak</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background: #f8fafc;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: 'Inter', sans-serif;
            }
            .error-card {
                background: white;
                padding: 2rem 3rem;
                border-radius: 1rem;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                text-align: center;
            }
            .error-code {
                font-size: 5rem;
                font-weight: 700;
                color: #dc3545;
            }
            .error-message {
                font-size: 1.2rem;
                color: #555;
                margin-bottom: 1.5rem;
            }
            .btn-home {
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="error-card mx-4">
            <div class="error-code">403</div>
            <div class="error-message">
                {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-primary btn-home">Back</a>
        </div>
    </body>
</html> --}}

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Akses Ditolak | 403</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #007bff;
      --danger: #e63946;
      --dark-bg: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
      --light-glass: rgba(255, 255, 255, 0.1);
      --text-light: #e2e8f0;
    }

    html, body {
      background: var(--dark-bg);
      color: var(--text-light);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      width: 100vw;
      font-family: 'Inter', sans-serif;
      overflow: hidden;
      position: relative;
    }

    /* Floating gradient background orbs */
    .orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(100px);
      opacity: 0.6;
      animation: float 10s infinite alternate ease-in-out;
      overflow: hidden;
      pointer-events: none;
      z-index: 0;
      will-change: transform; /* bantu performa animasi */
    }
    .orb.one {
      width: 400px;
      height: 400px;
      background: #2563eb;
      top: -100px;
      left: -100px;
    }
    .orb.two {
      width: 300px;
      height: 300px;
      background: #ec4899;
      bottom: -80px;
      right: -80px;
    }
    @keyframes float {
      from { transform: translateY(0); }
      to { transform: translateY(-30px); }
    }

    /* Card Styling */
    .error-card {
      position: relative;
      z-index: 2;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      border-radius: 1.5rem;
      padding: 3rem 3.5rem;
      text-align: center;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
      max-width: 450px;
      width: 90%;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .error-code {
      font-size: 6rem;
      font-weight: 900;
      background: linear-gradient(90deg, #60a5fa, #f87171, #fb923c);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
      margin-bottom: 1rem;
    }

    .error-message {
      font-size: 1.1rem;
      font-weight: 500;
      color: #e2e8f0;
      margin-bottom: 2rem;
    }

    .btn-home {
      background: linear-gradient(90deg, #3b82f6, #06b6d4);
      border: none;
      font-weight: 600;
      padding: 0.7rem 1.5rem;
      border-radius: 0.8rem;
      color: white;
      transition: all 0.3s ease;
    }
    .btn-home:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #06b6d4, #3b82f6);
      box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
    }

    @media (max-width: 576px) {
      .error-card {
        padding: 2rem;
      }
      .error-code {
        font-size: 4.5rem;
      }
      .error-message {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="orb one"></div>
  <div class="orb two"></div>

  <div class="error-card">
    <div class="error-code">403</div>
    <div class="error-message">
      {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
    </div>
    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn btn-home">⇽ Back</a>
  </div>
</body>
</html>
