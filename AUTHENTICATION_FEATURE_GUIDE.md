# Fitur Autentikasi dengan Email Verification dan Password Reset

## Daftar Isi
1. [Ringkasan Fitur](#ringkasan-fitur)
2. [Cara Kerja](#cara-kerja)
3. [Instalasi dan Konfigurasi](#instalasi-dan-konfigurasi)
4. [Panduan Penggunaan](#panduan-penggunaan)
5. [Keamanan](#keamanan)
6. [Troubleshooting](#troubleshooting)

---

## Ringkasan Fitur

Fitur autentikasi yang telah diimplementasikan mencakup:

### 1. **Email Verification (Verifikasi Email)**
- ✅ Setiap user yang baru mendaftar akan menerima email verifikasi
- ✅ User hanya bisa login setelah email diverifikasi
- ✅ Token verifikasi unik dan aman
- ✅ Fitur resend verification email jika email tidak diterima

### 2. **Forgot Password (Lupa Password)**
- ✅ User dapat meminta reset password melalui form
- ✅ Sistem mengirimkan email dengan link reset password yang aman
- ✅ Link valid selama 1 jam (dapat diubah di controller)
- ✅ Keamanan tinggi dengan token unik per user

### 3. **Email Dengan Format HTML Profesional**
- ✅ Template email HTML yang menarik dan responsif
- ✅ Pesan yang jelas dan mudah dipahami
- ✅ Tombol call-to-action yang prominent
- ✅ Informasi keamanan yang jelas

### 4. **Praktik Keamanan Standar**
- ✅ Token unik 64-character hex (menggunakan `random_bytes(32)`)
- ✅ Ekspirasi token (1 jam untuk password reset, tidak ada limit untuk email verification namun bisa diubah)
- ✅ One-time use tokens (token dihapus setelah digunakan)
- ✅ Hashing password dengan bcrypt
- ✅ CSRF protection pada semua form

---

## Cara Kerja

### Flow Registrasi dan Email Verification

```
1. User mengisi form register
   ↓
2. Sistem membuat akun dengan status email belum terverifikasi
   ↓
3. Sistem generate token verifikasi unik
   ↓
4. Email verifikasi dikirim ke alamat email user
   ↓
5. User klik link / masukkan token
   ↓
6. Sistem verifikasi token
   ↓
7. Email di-mark sebagai verified
   ↓
8. User dapat login
```

### Flow Forgot Password

```
1. User klik "Forgot Password" di halaman login
   ↓
2. Masukkan email terdaftar
   ↓
3. Sistem generate token reset & set expiry time
   ↓
4. Email reset password dikirim
   ↓
5. User klik link atau masukkan token
   ↓
6. Sistem verifikasi token & expiry time
   ↓
7. User masukkan password baru
   ↓
8. Sistem update password & clear reset token
   ↓
9. Redirect ke login untuk login dengan password baru
```

---

## Instalasi dan Konfigurasi

### Step 1: Jalankan Migration

```bash
php artisan migrate
```

Ini akan menambahkan kolom-kolom berikut ke tabel `users`:
- `email_verified_at` - timestamp ketika email diverifikasi
- `email_verification_token` - token unik untuk verifikasi email
- `password_reset_token` - token unik untuk reset password
- `password_reset_expires_at` - waktu kadaluarsa reset token

### Step 2: Konfigurasi Mail

Edit file `.env` dan pastikan mail configuration sudah benar:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD="your-app-password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tixly.com
MAIL_FROM_NAME="TIXLY"
```

**Catatan untuk Gmail:**
- Gunakan App Password bukan password Gmail biasa
- Aktifkan 2-Factor Authentication di akun Gmail
- Buat App Password di [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)

### Step 3: Test Email Configuration

Jalankan command untuk test email:

```bash
php artisan tinker

# Di dalam tinker console:
Mail::to('test@example.com')->send(new \App\Mail\VerifyEmailMail(new \App\Models\User, 'test-token'));
```

---

## Panduan Penggunaan

### Untuk Pengguna

#### 1. **Registrasi**

1. Kunjungi halaman `/register`
2. Isikan form:
   - Nama lengkap
   - Email aktif
   - Password (minimal 8 karakter)
   - Konfirmasi password
3. Klik "Sign Up"
4. Pesan sukses akan menampilkan instruksi untuk verifikasi email
5. Cek email Anda (juga cek folder Spam)
6. Klik link verifikasi atau copy-paste token
7. Akun Anda sekarang terverifikasi!

#### 2. **Login**

1. Kunjungi halaman `/login`
2. Isikan email dan password
3. Klik "Sign In"
4. Jika email belum diverifikasi, Anda akan mendapat error message dengan link untuk pilih ulang verifikasi email

#### 3. **Lupa Password**

1. Di halaman login, klik link "Forgot password?"
2. Masukkan email terdaftar
3. Klik "Kirim Link Reset Password"
4. Cek email (juga cek folder Spam)
5. Klik link reset password
6. Masukkan password baru (minimal 8 karakter)
7. Konfirmasi password baru
8. Klik "Reset Password"
9. Login dengan password baru

### Untuk Developer

#### Routes yang Tersedia

```php
// Public Registration & Email Verification
GET     /register                          → Register form
POST    /register                          → Process registration
GET     /verify-email                      → Email verification form
POST    /verify-email                      → Process email verification
POST    /resend-verification-email         → Resend verification email

// Password Reset
GET     /forgot-password                   → Forgot password form
POST    /forgot-password                   → Process forgot password request
GET     /reset-password                    → Reset password form (dengan token & email)
POST    /reset-password                    → Process password reset

// Login & Logout
GET     /login                             → Login form
POST    /login                             → Process login
POST    /logout                            → Process logout (auth required)
```

#### Controllers

- **AuthController** (`app/Http/Controllers/AuthController.php`)
  - `showRegister()` - Tampilkan form registrasi
  - `register()` - Proses registrasi
  - `showVerifyEmail()` - Tampilkan form verifikasi email
  - `verifyEmail()` - Proses verifikasi email
  - `resendVerificationEmail()` - Kirim ulang email verifikasi
  - `showLogin()` - Tampilkan form login
  - `login()` - Proses login
  - `showForgotPassword()` - Tampilkan form lupa password
  - `forgotPassword()` - Proses lupa password
  - `showResetPassword()` - Tampilkan form reset password
  - `resetPassword()` - Proses reset password
  - `logout()` - Proses logout

#### Models

**User Model** (`app/Models/User.php`) - Methods baru:
- `isEmailVerified()` - Check apakah email sudah diverifikasi
- `markEmailAsVerified()` - Mark email sebagai verified
- `generateEmailVerificationToken()` - Generate token verifikasi
- `generatePasswordResetToken()` - Generate token reset password
- `isPasswordResetTokenValid()` - Check apakah token valid & tidak expired
- `isEmailVerificationTokenValid()` - Check apakah token verifikasi valid
- `clearPasswordResetToken()` - Clear reset token setelah digunakan

#### Mail Classes

**VerifyEmailMail** (`app/Mail/VerifyEmailMail.php`)
```php
// Usage
Mail::send(new VerifyEmailMail($user, $verificationToken));
```

**ResetPasswordMail** (`app/Mail/ResetPasswordMail.php`)
```php
// Usage
Mail::send(new ResetPasswordMail($user, $resetToken));
```

#### Form Requests (Validation)

- `app/Http/Requests/VerifyEmailRequest.php` - Validasi email verification
- `app/Http/Requests/ForgotPasswordRequest.php` - Validasi forgot password
- `app/Http/Requests/ResetPasswordRequest.php` - Validasi reset password

---

## Keamanan

### Token Security

1. **Token Generation**
   - Menggunakan `random_bytes(32)` untuk generate 64-character hex string
   - Setiap token unik dan tidak dapat ditebak

2. **Token Storage**
   - Token disimpan di database (hashed atau plain, tergantung kebutuhan)
   - Token tidak pernah dikirim ulang

3. **Token Expiration**
   - Password reset token: **1 jam** (dapat diubah di `generatePasswordResetToken()`)
   - Email verification token: **tidak ada expiration** (dapat ditambahkan)
   - Token dihapus setelah digunakan

4. **One-Time Use**
   - Token dihapus dari database setelah digunakan
   - Token tidak dapat digunakan dua kali

### Password Security

- Menggunakan bcrypt hashing (default Laravel)
- Minimum 8 karakter
- CSRF protection pada semua form

### Email Security

- Email verifikasi hanya berisi link, tidak ada password
- Link reset password hanya valid untuk 1 jam
- User tidak perlu klik link, bisa copy-paste token juga

### Best Practices

1. **Gunakan HTTPS di production**
   - Semua link dan form harus melalui HTTPS
   - Set `APP_URL=https://...` di `.env`

2. **Rate Limiting** (Optional)
   ```php
   Route::middleware('throttle:5,1')->group(function () {
       Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
       Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail']);
   });
   ```

3. **Logging**
   - Log semua aktivitas login, registration, dan password reset
   - Monitor suspicious activities

---

## Troubleshooting

### Email tidak terima

**Masalah:** User tidak menerima email verifikasi atau reset password

**Solusi:**
1. Cek folder Spam/Junk email
2. Verifikasi configuration email di `.env`
3. Test email dengan command: `php artisan tinker`
4. Cek logs: `tail -f storage/logs/laravel.log`
5. Jika menggunakan Gmail, pastikan App Password digunakan (bukan password biasa)

### Token expired

**Masalah:** "Token tidak valid atau sudah kadaluarsa"

**Solusi:**
1. Untuk password reset: Link hanya valid 1 jam, request link baru
2. Untuk email verification: Tidak ada expiration, tapi bisa resend email jika ada masalah
3. Ubah expiration time di `generatePasswordResetToken()`:
   ```php
   $expiresAt = now()->addHours(2); // 2 jam
   ```

### User tidak bisa login after verification

**Masalah:** Email sudah diverifikasi tapi login tetap gagal

**Solusi:**
1. Cek apakah status `email_verified_at` sudah di-set ke `now()` di database
2. Run command untuk check:
   ```bash
   php artisan tinker
   \App\Models\User::where('email', 'user@example.com')->first()->isEmailVerified()
   ```
3. Jika belum, update manual:
   ```php
   $user = \App\Models\User::where('email', 'user@example.com')->first();
   $user->markEmailAsVerified();
   ```

### SMTP Connection Error

**Masalah:** "SMTP Connection refused" atau "Authentication failed"

**Solusi:**
1. Verifikasi kredensial email di `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD="your-app-password"
   MAIL_ENCRYPTION=tls
   ```
2. Jika menggunakan Gmail, gunakan App Password (bukan password Gmail)
3. Untuk development, gunakan Mailtrap (mailtrap.io):
   ```env
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=465
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_ENCRYPTION=tls
   ```

### Reset token tetap ada di database

**Masalah:** Setelah reset password, token masih ada di database

**Solusi:**
- Ini adalah issue minor, token sudah tidak valid karena sudah expired
- Bisa di-clean up dengan command:
  ```bash
  php artisan tinker
  \App\Models\User::where('password_reset_expires_at', '<', now())->update(['password_reset_token' => null])
  ```

---

## File-file yang Dibuat/Dimodifikasi

### Files Baru
- ✅ `database/migrations/2026_03_26_000001_add_email_verification_to_users_table.php`
- ✅ `app/Mail/VerifyEmailMail.php`
- ✅ `app/Mail/ResetPasswordMail.php`
- ✅ `app/Http/Requests/VerifyEmailRequest.php`
- ✅ `app/Http/Requests/ForgotPasswordRequest.php`
- ✅ `app/Http/Requests/ResetPasswordRequest.php`
- ✅ `resources/views/auth/verify-email.blade.php`
- ✅ `resources/views/auth/forgot-password.blade.php`
- ✅ `resources/views/auth/reset-password.blade.php`
- ✅ `resources/views/emails/verify-email.blade.php`
- ✅ `resources/views/emails/reset-password.blade.php`

### Files Dimodifikasi
- ✅ `app/Http/Controllers/AuthController.php` - Tambah methods untuk email verification & password reset
- ✅ `app/Models/User.php` - Tambah fields dan helper methods
- ✅ `routes/web.php` - Tambah routes untuk email verification & password reset
- ✅ `resources/views/auth/login.blade.php` - Update forgot password link

---

## Notes Implementasi

### Custom Fields
Proyek ini menggunakan custom fields di User model:
- `pass` (bukan `password`) untuk menyimpan password hash
- `user_id` (string, bukan auto-increment integer) sebagai primary key
- `role_id` (foreign key ke tabel roles) untuk role-based access

Semua handling ini sudah di-implementasi di User model dengan helper methods dan overrides.

### Email Queue (Optional)
Untuk better performance, Anda bisa configure email queue:

```env
QUEUE_CONNECTION=database
```

Kemudian jalankan:
```bash
php artisan queue:work
```

Ini akan mengirim email secara asynchronous sehingga tidak block user request.

---

## Support & Dokumentasi Tambahan

- Laravel Mail Documentation: https://laravel.com/docs/mail
- Laravel Authentication: https://laravel.com/docs/authentication
- Laravel Validation: https://laravel.com/docs/validation

---

**Dibuat:** 26 Maret 2026  
**Versi:** 1.0  
**Status:** ✅ Siap Digunakan
