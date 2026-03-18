<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LuxTix Event Platform</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .auth-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .auth-subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 20px;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .auth-link a {
            color: #667eea;
            text-decoration: none;
        }

        .error-list {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            padding-left: 0;
            list-style: none;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-title">Welcome Back</div>
        <div class="auth-subtitle">Login to your LuxTix account</div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Login failed!</strong>
                <ul class="mb-0">
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
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                    <ul class="error-list">
                        <li>{{ $message }}</li>
                    </ul>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group">
                <label class="remember-me">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <div class="auth-link">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
    </div>
</body>

</html>
