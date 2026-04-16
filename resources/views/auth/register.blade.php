<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TIXLY | Join the Experience</title>
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
            background: url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=1200&q=80') center/cover no-repeat;
            filter: brightness(0.3);
        }

        .left-panel-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(139,0,0,0.45) 0%, rgba(0,0,0,0.75) 100%);
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
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
            color: #fff;
        }

        .left-sub {
            font-size: 1rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.7;
            margin-bottom: 50px;
        }

        .stats-row {
            display: flex;
            gap: 36px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            color: #dc143c;
            font-family: 'DM Serif Display', serif;
        }

        .stat-label {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* Right Panel */
        .right-panel {
            width: 520px;
            flex-shrink: 0;
            background: #111111;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 50px;
            overflow-y: auto;
        }

        .form-box {
            width: 100%;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #ffffff;
            font-family: 'DM Serif Display', serif;
            margin-bottom: 8px;
        }

        .form-subtitle {
            color: rgba(255,255,255,0.4);
            font-size: 0.88rem;
        }

        .form-subtitle a {
            color: #dc143c;
            text-decoration: none;
            font-weight: 600;
        }
        .form-subtitle a:hover { text-decoration: underline; }

        .alert-error {
            background: rgba(220,20,60,0.1);
            border: 1px solid rgba(220,20,60,0.3);
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

        .alert-error ul { margin: 0; padding-left: 18px; }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
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
            color: rgba(255,255,255,0.2);
            font-size: 0.85rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 10px;
            padding: 12px 16px 12px 42px;
            color: #fff;
            font-size: 0.92rem;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
        }

        .form-input::placeholder { color: rgba(255,255,255,0.18); }

        .form-input:focus {
            border-color: #dc143c;
            background: rgba(220,20,60,0.06);
            box-shadow: 0 0 0 3px rgba(220,20,60,0.14);
        }

        .form-input:focus ~ .input-icon,
        .input-wrap:focus-within .input-icon { color: #dc143c; }

        .form-input.is-invalid { border-color: #ff6b6b; }

        .input-error {
            color: #ff8a8a;
            font-size: 0.78rem;
            margin-top: 5px;
        }

        /* Role selector */
        .role-section-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
        }

        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .role-card input[type="radio"] { display: none; }

        .role-card label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 16px 12px;
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            cursor: pointer;
            text-align: center;
            transition: all 0.25s;
            background: rgba(255,255,255,0.03);
        }

        .role-card label:hover {
            border-color: rgba(220,20,60,0.4);
            background: rgba(220,20,60,0.05);
        }

        .role-card input[type="radio"]:checked + label {
            border-color: #dc143c;
            background: rgba(220,20,60,0.12);
            box-shadow: 0 0 0 2px rgba(220,20,60,0.2);
        }

        .role-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: rgba(255,255,255,0.4);
            transition: all 0.25s;
        }

        .role-card input[type="radio"]:checked + label .role-icon {
            background: rgba(220,20,60,0.2);
            color: #ff6b6b;
        }

        .role-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
        }

        .role-desc {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.35);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.25s;
            margin-top: 4px;
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
            margin: 22px 0;
            color: rgba(255,255,255,0.15);
            font-size: 0.8rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
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

        /* Particles */
        .particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(220,20,60,0.5);
            animation: float linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 0.4; }
            100% { transform: translateY(-100px) scale(1); opacity: 0; }
        }

        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 24px; }
            .form-grid-2 { grid-template-columns: 1fr; }
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
            <h1 class="left-headline">Join the<br>World's Best<br>Live Events.</h1>
            <p class="left-sub">Create your account and gain access to thousands of concerts, festivals, and live events around the world.</p>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-num">10K+</div>
                    <div class="stat-label">Events</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">500K+</div>
                    <div class="stat-label">Tickets Sold</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">98%</div>
                    <div class="stat-label">Satisfaction</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="form-box">
            <div class="form-header">
                <h2 class="form-title">Create Account</h2>
                <p class="form-subtitle">
                    Already have an account? <a href="{{ route('login') }}">Sign in here</a>
                </p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <strong><i class="fas fa-exclamation-circle"></i> Registration Failed</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div class="input-wrap">
                        <input type="text"
                            class="form-input @error('name') is-invalid @enderror"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Your full name"
                            required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    @error('name')
                        <div class="input-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrap">
                        <input type="email"
                            class="form-input @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="your@email.com"
                            required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <div class="input-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrap">
                            <input type="password"
                                class="form-input @error('password') is-invalid @enderror"
                                name="password"
                                placeholder="Min. 8 characters"
                                required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        @error('password')
                            <div class="input-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-wrap">
                            <input type="password"
                                class="form-input"
                                name="password_confirmation"
                                placeholder="Repeat password"
                                required>
                            <i class="fas fa-key input-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <!-- Set default role as user -->
                    <input type="hidden" name="role" value="user">
                </div>

                <button type="submit" class="btn-submit" id="registerBtn">
                    <i class="fas fa-user-plus" style="margin-right: 8px;"></i>
                    Create My Account
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
        const container = document.getElementById('particles');
        for (let i = 0; i < 18; i++) {
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
