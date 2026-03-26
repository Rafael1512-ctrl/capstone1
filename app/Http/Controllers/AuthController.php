<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyEmailMail;
use App\Mail\ResetPasswordMail;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Show register page
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $hashedPassword = Hash::make($validated['password']);

        DB::statement(
            "CALL AddUser(?, ?, ?, ?)",
            [$validated['name'], $validated['email'], $hashedPassword, 3] // role_id 3 = user
        );

        $user = User::where('email', $validated['email'])->first();

        // Generate email verification token
        $verificationToken = $user->generateEmailVerificationToken();

        // Send verification email
        Mail::send(new VerifyEmailMail($user, $verificationToken));

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan periksa email Anda untuk memverifikasi akun.');
    }

    // Show verify email page
    public function showVerifyEmail(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        return view('auth.verify-email', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Handle email verification
    public function verifyEmail(VerifyEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if (!$user->isEmailVerificationTokenValid($request->token)) {
            return back()->withErrors(['token' => 'Token verifikasi tidak valid atau sudah kadaluarsa.']);
        }

        $user->markEmailAsVerified();

        return redirect()->route('login')->with('success', 'Email Anda telah berhasil diverifikasi. Silakan login untuk melanjutkan.');
    }

    // Resend verification email
    public function resendVerificationEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if ($user->isEmailVerified()) {
            return back()->with('info', 'Email Anda sudah terverifikasi sebelumnya.');
        }

        $verificationToken = $user->generateEmailVerificationToken();
        Mail::send(new VerifyEmailMail($user, $verificationToken));

        return back()->with('success', 'Link verifikasi telah dikirim ke email Anda.');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Bypassing Auth::attempt to avoid issues with custom 'pass' column and missing remember_token
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        // Check if email is verified
        if (!$user->isEmailVerified()) {
            return back()->withErrors([
                'email' => 'Email Anda belum diverifikasi. Silakan cek email untuk link verifikasi.',
            ])->onlyInput('email');
        }

        if (Hash::check($credentials['password'], $user->pass)) {
            Auth::login($user, $remember);
            $request->session()->regenerate();

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isOrganizer()) {
                return redirect()->route('organizer.dashboard');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Show forgot password page
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Handle forgot password request
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // For security, don't reveal if email exists
            return back()->with('success', 'Jika email Anda terdaftar, kami akan mengirimkan link reset password.');
        }

        // Generate password reset token
        $resetToken = $user->generatePasswordResetToken();

        // Send password reset email
        Mail::send(new ResetPasswordMail($user, $resetToken));

        return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek email Anda.');
    }

    // Show reset password page
    public function showResetPassword(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        $user = User::where('email', $email)->first();

        if (!$user || !$user->isPasswordResetTokenValid($token)) {
            return redirect()->route('forgot-password')->withErrors(['token' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    // Handle password reset
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if (!$user->isPasswordResetTokenValid($request->token)) {
            return back()->withErrors(['token' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
        }

        // Update password
        $user->update([
            'pass' => Hash::make($request->password),
        ]);

        // Clear reset token
        $user->clearPasswordResetToken();

        return redirect()->route('login')->with('success', 'Password Anda berhasil diubah. Silakan login dengan password baru Anda.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}