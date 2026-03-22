<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LuxTix Event Platform</title>
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
            max-width: 450px;
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

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 20px;
        }

        .btn-register:hover {
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

        .role-select {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .role-option {
            flex: 1;
            position: relative;
        }

        .role-option input[type="radio"] {
            display: none;
        }

        .role-option label {
            display: block;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 0;
        }

        .role-option input[type="radio"]:checked+label {
            border-color: #667eea;
            background: #f0f4ff;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-title">Create Account</div>
        <div class="auth-subtitle">Join LuxTix to buy and sell event tickets</div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Registration failed!</strong>
                <ul class="mb-0">
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
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name') }}" required>
                @error('name')
                    <ul class="error-list">
                        <li>{{ $message }}</li>
                    </ul>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required>
                @error('email')
                    <ul class="error-list">
                        <li>{{ $message }}</li>
                    </ul>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label class="form-label">I am a:</label>
                <div class="role-select">
                    <div class="role-option">
                        <input type="radio" id="role_user" name="role" value="user"
                            {{ old('role') === 'user' ? 'checked' : '' }} required>
                        <label for="role_user">
                            <strong>Buyer</strong><br>
                            <small>Purchase tickets</small>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="role_organizer" name="role" value="organizer"
                            {{ old('role') === 'organizer' ? 'checked' : '' }} required>
                        <label for="role_organizer">
                            <strong>Organizer</strong><br>
                            <small>Sell tickets</small>
                        </label>
                    </div>
                </div>
                @error('role')
                    <ul class="error-list">
                        <li>{{ $message }}</li>
                    </ul>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                    required>
                @error('password')
                    <ul class="error-list">
                        <li>{{ $message }}</li>
                    </ul>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <div class="auth-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
</body>

</html>
