<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - TIXLY</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
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
            box-shadow: 0 10px 50px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            padding: 50px;
            text-align: center;
        }
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(90deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 40px;
            display: inline-block;
        }
        .icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
            display: block;
        }
        h1 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 0.95rem;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
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
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
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
            font-family: 'Inter', sans-serif;
            margin-top: 20px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
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
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .resend-link {
            margin-top: 20px;
            text-align: center;
        }
        .resend-link p {
            color: #666;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        .resend-link form {
            display: inline;
        }
        .resend-btn {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            text-decoration: underline;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
        }
        .resend-btn:hover {
            color: #764ba2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">TIXLY</div>
        
        <i class="fas fa-envelope-circle-check icon"></i>
        
        <h1>Verifikasi Email Anda</h1>
        
        <p class="subtitle">
            Kami telah mengirimkan email verifikasi ke <strong>{{ $email }}</strong>. 
            Silakan masukkan token yang ada di email untuk memverifikasi akun Anda.
        </p>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <form action="{{ route('verify-email.post') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ $email }}" readonly>
            </div>
            
            <div class="form-group">
                <label for="token">Token Verifikasi</label>
                <input 
                    type="text" 
                    id="token" 
                    name="token" 
                    placeholder="Masukkan token dari email Anda"
                    required
                    value="{{ $token }}"
                >
            </div>
            
            <button type="submit" class="btn-primary">
                <i class="fas fa-check"></i> Verifikasi Email
            </button>
        </form>
        
        <div class="resend-link">
            <p>Tidak menerima email?</p>
            <form action="{{ route('resend-verification-email') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="resend-btn">Kirim ulang email verifikasi</button>
            </form>
        </div>
    </div>
</body>

</html>
