<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#3d6f46">
    <title>Login - Diesel Parts Inventory System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --matcha-bg: #f2f7f3;
            --matcha-primary: A#3d6f46;
            --matcha-primary-hover: #315a39;
            --matcha-dark: #19311e;
            --text-main: #18281d;
            --text-muted: #5e7464;
            --border-color: #d8e5da;
            --radius-md: 10px;
            --radius-lg: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #172c1c 0%, #294d31 60%, #3e6d47 100%);
            color: var(--text-main);
            position: relative;
            overflow-x: hidden;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 38px 34px;
            background: #ffffff;
            border-radius: var(--radius-lg);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.22);
            position: relative;
            z-index: 10;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #4fa15c, #2e5936);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 26px;
            margin-bottom: 12px;
            box-shadow: 0 6px 16px rgba(79, 161, 92, 0.35);
        }

        h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--matcha-dark);
            letter-spacing: -0.4px;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
        }

        label {
            display: block;
            margin: 16px 0 7px;
            font-weight: 600;
            font-size: 13.5px;
            color: var(--text-main);
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s;
            background: #fbfdfb;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--matcha-primary);
            box-shadow: 0 0 0 3px rgba(61, 111, 70, 0.18);
            background: #ffffff;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 18px 0;
            color: var(--text-muted);
            font-size: 13.5px;
            cursor: pointer;
        }

        .remember input {
            accent-color: var(--matcha-primary);
            width: 16px;
            height: 16px;
        }

        button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: var(--radius-md);
            background: var(--matcha-primary);
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 0 4px 12px rgba(61, 111, 70, 0.28);
            transition: all 0.2s;
        }

        button:hover {
            background: var(--matcha-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(61, 111, 70, 0.35);
        }

        button:active {
            transform: translateY(0);
        }

        .error {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            background: #fee2e2;
            color: #991b1b;
            font-size: 13.5px;
            border: 1px solid #fca5a5;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .demo-accounts {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px dashed var(--border-color);
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .demo-accounts strong {
            color: var(--matcha-dark);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-header">
            <div class="brand-icon">🌿</div>
            <h1>Diesel Parts</h1>
            <p class="subtitle">Sistem Manajemen Persediaan & Penjualan</p>
        </div>

        @if ($errors->any())
            <div class="error">
                <span>⚠️</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <label for="email">Email Pengguna</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@dieselparts.test"
                required
                autofocus
                autocomplete="username"
            >

            <label for="password">Kata Sandi</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="••••••••"
                required
                autocomplete="current-password"
            >

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat status login
            </label>

            <button type="submit">Masuk ke Sistem</button>
        </form>

        <div class="demo-accounts">
            <div><strong>Akun Demo Tersedia:</strong></div>
            <div>• Admin: <code>admin@dieselparts.test</code> (Akses Laporan Keuangan)</div>
            <div>• Staff: <code>staff@dieselparts.test</code></div>
        </div>
    </div>
</body>
</html>
