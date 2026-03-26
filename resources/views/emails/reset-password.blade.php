<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - TIXLY</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .message {
            font-size: 14px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            padding: 12px 40px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .cta-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .link-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            word-break: break-all;
        }
        
        .link-container p {
            font-size: 12px;
            color: #777;
            margin-bottom: 10px;
        }
        
        .link-container a {
            color: #667eea;
            font-size: 13px;
            text-decoration: none;
        }
        
        .footer {
            background-color: #f5f5f5;
            padding: 20px 30px;
            text-align: center;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #e0e0e0;
        }
        
        .security-notice {
            background-color: #f8d7da;
            border-left: 4px solid #f5c6cb;
            padding: 15px;
            margin-top: 30px;
            font-size: 12px;
            color: #721c24;
            border-radius: 3px;
        }
        
        .expiry-notice {
            color: #dc3545;
            font-weight: bold;
            font-size: 13px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>TIXLY</h1>
            <p>Platform Penjualan Tiket Terpercaya</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo <strong>{{ $user->name }}</strong>,
            </div>
            
            <div class="message">
                Kami menerima permintaan untuk mereset password akun Anda. Jika ini adalah permintaan Anda, 
                silakan klik tombol di bawah ini untuk melanjutkan proses reset password:
            </div>
            
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="cta-button">Reset Password Saya</a>
            </div>
            
            <div class="message" style="text-align: center; font-size: 13px; color: #999;">
                atau salin tautan di bawah ini ke browser Anda:
            </div>
            
            <div class="link-container">
                <p><strong>Link Reset Password:</strong></p>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>
            
            <div class="security-notice">
                <strong>⚠️ Keamanan Penting:</strong><br>
                • Link reset password ini hanya berlaku untuk satu kali penggunaan<br>
                • Jangan pernah bagikan link ini kepada siapa pun<br>
                • Jika Anda tidak meminta reset password, abaikan email ini dan pastikan akun Anda aman<br>
                • Tidak ada yang bisa mengubah password Anda tanpa link ini<br>
                <span class="expiry-notice">• Link akan kadaluarsa dalam 1 jam</span>
            </div>
            
            <div class="message" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
                Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini dengan aman. 
                Akun Anda tetap aman dengan password saat ini.
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>© 2026 TIXLY - Platform Penjualan Tiket. All rights reserved.</p>
            <p>Email ini dikirim karena adanya permintaan reset password di TIXLY.</p>
        </div>
    </div>
</body>
</html>
