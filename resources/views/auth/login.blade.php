
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory System</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 32px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0,0,0,.08);
        }

        h1 { margin: 0 0 8px; font-size: 25px; }
        .subtitle { margin-bottom: 26px; color: #64748b; }

        label {
            display: block;
            margin: 16px 0 8px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 16px 0;
            color: #475569;
        }

        button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover { background: #1d4ed8; }

        .error {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 8px;
            background: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Inventory System</h1>
        <p class="subtitle">Silakan login untuk melanjutkan.</p>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
            >

            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            >

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
