<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - TIXLY</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            padding: 50px;
        }

        .logo {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(90deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 40px;
            text-align: center;
            display: block;
        }

        .icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
            display: block;
            text-align: center;
        }

        h1 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 700;
            text-align: center;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 0.95rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.3s ease;
        }

        input[type="password"] {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: 0.15em;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            width: 100%;
            padding: 12px;
            background: #f0f0f0;
            color: #333;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 10px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-requirements {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            color: #666;
        }

        .password-requirements h4 {
            margin-bottom: 8px;
            color: #333;
            font-size: 0.9rem;
        }

        .password-requirements ul {
            list-style: none;
            padding-left: 0;
        }

        .password-requirements li {
            padding: 4px 0;
        }

        .password-requirements li::before {
            content: "✓ ";
            color: #667eea;
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">TIXLY</div>

        <i class="fas fa-lock icon"></i>

        <h1>Reset Password</h1>

        <p class="subtitle">
            Masukkan password baru Anda. Pastikan password kuat dan mudah diingat.
        </p>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="password-requirements">
            <h4><i class="fas fa-shield-alt"></i> Persyaratan Password:</h4>
            <ul>
                <li>Minimal 8 karakter</li>
                <li>Gunakan kombinasi huruf besar, kecil, dan angka</li>
                <li>Jangan gunakan password yang mudah ditebak</li>
            </ul>
        </div>

        <form action="{{ route('reset-password') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password baru" required
                    minlength="8">
                @error('password')
                    <small style="color: #dc3545; margin-top: 5px; display: block;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Konfirmasi password baru" required minlength="8">
                @error('password_confirmation')
                    <small style="color: #dc3545; margin-top: 5px; display: block;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-check"></i> Reset Password
            </button>

            <a href="{{ route('login') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Login
            </a>
        </form>

        <div class="login-link">
            Ingat password Anda? <a href="{{ route('login') }}">Login sekarang</a>
        </div>
    </div>
</body>

</html>
