<!DOCTYPE html>
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
</html>
