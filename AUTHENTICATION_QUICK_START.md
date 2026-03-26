# Quick Start - Fitur Autentikasi

## ⚡ Setup Cepat (5 Menit)

### 1. Database Migration
```bash
php artisan migrate
```

### 2. Update .env - Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=apptixly@gmail.com
MAIL_PASSWORD="yliv kxni ervw sxyc"  # Sudah ada
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tixly.com
MAIL_FROM_NAME="TIXLY"
```

### 3. Test Email (Optional)
```bash
php artisan tinker
mail('test@example.com')->send(new \App\Mail\VerifyEmailMail(...))
```

### 4. Jalankan Server
```bash
php artisan serve
```

### 5. Test Features
- Register: http://localhost:8000/register
- Login: http://localhost:8000/login
- Forgot Password: http://localhost:8000/forgot-password

---

## 📋 Routes Available

| Method | Route | Controller Method | Keterangan |
|--------|-------|-------------------|-----------|
| GET | `/register` | showRegister | Form registrasi |
| POST | `/register` | register | Proses registrasi |
| GET | `/login` | showLogin | Form login |
| POST | `/login` | login | Proses login |
| GET | `/verify-email` | showVerifyEmail | Form verifikasi email |
| POST | `/verify-email` | verifyEmail | Proses verifikasi |
| POST | `/resend-verification-email` | resendVerificationEmail | Kirim ulang email |
| GET | `/forgot-password` | showForgotPassword | Form lupa password |
| POST | `/forgot-password` | forgotPassword | Proses lupa password |
| GET | `/reset-password` | showResetPassword | Form reset password (dengan token) |
| POST | `/reset-password` | resetPassword | Proses reset password |
| POST | `/logout` | logout | Logout |

---

## 🔐 Token Security

- **Email Verification Token**: 64-char hex, tidak ada expiry
- **Password Reset Token**: 64-char hex, expired dalam 1 jam
- **One-time use**: Token dihapus setelah digunakan
- **Random generation**: Menggunakan `random_bytes(32)`

---

## 📧 Email Templates

**Email Verification** - `resources/views/emails/verify-email.blade.php`
- Kirim ke user saat registrasi
- Berisi link verifikasi unique
- Valid selamanya sampai digunakan

**Password Reset** - `resources/views/emails/reset-password.blade.php`
- Kirim ke user saat forgot password
- Berisi link reset unique
- Valid 1 jam

---

## 🧪 Testing Scenarios

### Scenario 1: Registration Flow
1. Kunjungi `/register`
2. Isikan form (nama, email, password)
3. Submit
4. Cek email verification link
5. Klik link atau masukkan token
6. Email terverifikasi ✓
7. Login dengan credentials baru

### Scenario 2: Forgot Password
1. Kunjungi `/login`, klik "Forgot password?"
2. Masukkan email
3. Cek email reset link
4. Klik link atau masukkan token
5. Masukkan password baru
6. Login dengan password baru

### Scenario 3: Resend Verification
1. Register tapi "lupa" klik link
2. Di halaman verify-email, klik "Kirim ulang email verifikasi"
3. Email terkirim lagi
4. Klik link yang baru

---

## 🛠️ Troubleshooting

| Problem | Solution |
|---------|----------|
| Email tidak terima | Cek spam folder, verify SMTP config |
| Token expired | Password reset: 1 jam. Request baru jika expired |
| Login gagal after verify | Check `email_verified_at` di database |
| SMTP error | Verify MAIL_* di .env, test dengan Mailtrap |

---

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── AuthController.php ✏️ (Updated)
│   └── Requests/
│       ├── VerifyEmailRequest.php ✨ (New)
│       ├── ForgotPasswordRequest.php ✨ (New)
│       └── ResetPasswordRequest.php ✨ (New)
├── Mail/
│   ├── VerifyEmailMail.php ✨ (New)
│   └── ResetPasswordMail.php ✨ (New)
└── Models/
    └── User.php ✏️ (Updated)

database/
└── migrations/
    └── 2026_03_26_000001_add_email_verification_to_users_table.php ✨ (New)

resources/
├── views/
│   ├── auth/
│   │   ├── verify-email.blade.php ✨ (New)
│   │   ├── forgot-password.blade.php ✨ (New)
│   │   ├── reset-password.blade.php ✨ (New)
│   │   └── login.blade.php ✏️ (Updated)
│   └── emails/
│       ├── verify-email.blade.php ✨ (New)
│       └── reset-password.blade.php ✨ (New)

routes/
└── web.php ✏️ (Updated)
```

---

## 🚀 Next Steps

- [ ] Run migration: `php artisan migrate`
- [ ] Test email configuration
- [ ] Update `.env` with correct SMTP credentials
- [ ] Test registration flow
- [ ] Test forgot password flow
- [ ] Deploy to production with HTTPS

---

## 📞 Support

Untuk bantuan lebih lanjut, lihat `AUTHENTICATION_FEATURE_GUIDE.md`

Dibuat: 26 Maret 2026
