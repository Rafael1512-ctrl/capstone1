<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TIXLY | Experience the Magic</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            min-height: 100vh;
            display: flex;
            background: #0a0a0a;
            overflow: hidden;
        }

        /* Left Panel */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .left-panel-bg {
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1200&q=80') center/cover no-repeat;
            filter: brightness(0.35);
        }

        .left-panel-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(139,0,0,0.4) 0%, rgba(0,0,0,0.7) 100%);
        }

        .left-content {
            position: relative;
            z-index: 2;
            color: white;
            padding: 60px;
            max-width: 500px;
        }

        .brand-logo {
            font-family: 'DM Serif Display', serif;
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(90deg, #dc143c, #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 2px;
            margin-bottom: 50px;
            display: block;
            text-decoration: none;
        }

        .left-headline {
            font-family: 'DM Serif Display', serif;
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
            color: #fff;
        }

        .left-sub {
            font-size: 1rem;
            color: rgba(255,255,255,0.65);
            line-height: 1.7;
            margin-bottom: 50px;
        }

        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255,255,255,0.8);
            font-size: 0.93rem;
        }

        .feature-list li .icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(220,20,60,0.2);
            border: 1px solid rgba(220,20,60,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff6b6b;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        /* Right Panel */
        .right-panel {
            width: 480px;
            flex-shrink: 0;
            background: #111111;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            overflow-y: auto;
        }

        .form-box {
            width: 100%;
        }

        .form-header {
            margin-bottom: 36px;
        }

        .form-title {
            font-size: 1.9rem;
            font-weight: 800;
            color: #ffffff;
            font-family: 'DM Serif Display', serif;
            margin-bottom: 8px;
        }

        .form-subtitle {
            color: rgba(255,255,255,0.45);
            font-size: 0.9rem;
        }

        .form-subtitle a {
            color: #dc143c;
            text-decoration: none;
            font-weight: 600;
        }
        .form-subtitle a:hover { text-decoration: underline; }

        .alert-error {
            background: rgba(220,20,60,0.12);
            border: 1px solid rgba(220,20,60,0.35);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 24px;
            color: #ff8a8a;
            font-size: 0.875rem;
        }

        .alert-error strong {
            display: block;
            margin-bottom: 6px;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 18px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255,255,255,0.55);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.25);
            font-size: 0.9rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 13px 16px 13px 44px;
            color: #fff;
            font-size: 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
        }

        .form-input::placeholder { color: rgba(255,255,255,0.2); }

        .form-input:focus {
            border-color: #dc143c;
            background: rgba(220,20,60,0.06);
            box-shadow: 0 0 0 3px rgba(220,20,60,0.15);
        }

        .form-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon { color: #dc143c; }

        .form-input.is-invalid { border-color: #ff6b6b; }

        .input-error {
            color: #ff8a8a;
            font-size: 0.8rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            color: rgba(255,255,255,0.5);
            font-size: 0.87rem;
        }

        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #dc143c;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.87rem;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: #dc143c; }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0);
            transition: background 0.2s;
        }

        .btn-submit:hover::before { background: rgba(255,255,255,0.08); }

        .btn-submit:active { transform: scale(0.99); }

        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 28px 0;
            color: rgba(255,255,255,0.15);
            font-size: 0.8rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        .back-home {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.3);
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s;
            justify-content: center;
        }
        .back-home:hover { color: rgba(255,255,255,0.6); }

        /* Animated particles */
        .particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(220,20,60,0.5);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 0.5; }
            100% { transform: translateY(-100px) scale(1); opacity: 0; }
        }

        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 28px; }
        }
    </style>
</head>

<body>
    <!-- Left Panel -->
    <div class="left-panel">
        <div class="left-panel-bg"></div>
        <div class="left-panel-overlay"></div>
        <div class="particles" id="particles"></div>
        <div class="left-content">
            <a href="{{ route('home') }}" class="brand-logo">TIXLY</a>
            <h1 class="left-headline">Experience<br>the Magic.<br>Feel the Music.</h1>
            <p class="left-sub">The most exclusive access to the world's most anticipated concerts and live events.</p>
            <ul class="feature-list">
                <li>
                    <div class="icon-wrap"><i class="fas fa-ticket-alt"></i></div>
                    Instant e-ticket delivery
                </li>
                <li>
                    <div class="icon-wrap"><i class="fas fa-shield-alt"></i></div>
                    100% secure & verified tickets
                </li>
                <li>
                    <div class="icon-wrap"><i class="fas fa-star"></i></div>
                    Exclusive VIP seat access
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="form-box">
            <div class="form-header">
                <h2 class="form-title">Welcome Back</h2>
                <p class="form-subtitle">
                    Don't have an account? <a href="{{ route('register') }}">Create one</a>
                </p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <strong><i class="fas fa-exclamation-circle"></i> Login Failed</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrap">
                        <input type="email"
                            class="form-input @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="your@email.com"
                            required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <div class="input-error">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <input type="password"
                            class="form-input"
                            name="password"
                            id="passwordInput"
                            placeholder="Enter your password"
                            required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1">
                        Remember me
                    </label>
                    <a href="{{ route('forgot-password') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                    Sign In to TIXLY
                </button>
            </form>

            <div class="divider">or</div>

            <a href="{{ route('home') }}" class="back-home">
                <i class="fas fa-arrow-left"></i>
                Back to Homepage
            </a>
        </div>
    </div>

    <script>
        // Generate floating particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 22; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 4 + 2;
            p.style.cssText = `
                left: ${Math.random() * 100}%;
                width: ${size}px;
                height: ${size}px;
                animation-duration: ${Math.random() * 12 + 8}s;
                animation-delay: ${Math.random() * 10}s;
                opacity: ${Math.random() * 0.6};
            `;
            container.appendChild(p);
        }
    </script>
</body>

</html>
