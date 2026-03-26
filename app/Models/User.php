<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    
    // Primary key override
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // It seems the users table doesn't have created_at/updated_at

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'pass',
        'role_id',
        'email_verified_at',
        'email_verification_token',
        'password_reset_token',
        'password_reset_expires_at',
    ];

    protected $hidden = [
        'pass',
        'email_verification_token',
        'password_reset_token',
        'password_reset_expires_at',
    ];

    protected $casts = [
        'role_id' => 'integer',
        'email_verified_at' => 'datetime',
        'password_reset_expires_at' => 'datetime',
    ];

    // Tell Laravel to use 'pass' as the password field
    public function getAuthPassword()
    {
        return $this->pass;
    }

    public function getAuthPasswordName()
    {
        return 'pass';
    }

    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function getRoleAttribute()
    {
        // Mapekan role_id ke string name untuk middleware 'role:admin'
        $roles = [1 => 'admin', 2 => 'organizer', 3 => 'user'];
        return $roles[$this->role_id] ?? 'user';
    }

    public function isAdmin()
    {
        return $this->role_id === 1; // Assuming 1 is Admin based on standard setups
    }

    public function isOrganizer()
    {
        return $this->role_id === 2; // Assuming 2 is Organizer
    }

    public function isUser()
    {
        return $this->role_id === 3; // Assuming 3 is User
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    /**
     * Check if email is verified
     */
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        return $this->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);
    }

    /**
     * Generate email verification token
     */
    public function generateEmailVerificationToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->update(['email_verification_token' => $token]);
        return $token;
    }

    /**
     * Generate password reset token
     */
    public function generatePasswordResetToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = now()->addHours(1); // Token valid for 1 hour
        $this->update([
            'password_reset_token' => $token,
            'password_reset_expires_at' => $expiresAt,
        ]);
        return $token;
    }

    /**
     * Check if password reset token is valid and not expired
     */
    public function isPasswordResetTokenValid(string $token): bool
    {
        return $this->password_reset_token === $token &&
               $this->password_reset_expires_at &&
               $this->password_reset_expires_at->isFuture();
    }

    /**
     * Check if email verification token is valid
     */
    public function isEmailVerificationTokenValid(string $token): bool
    {
        return $this->email_verification_token === $token &&
               !$this->isEmailVerified();
    }

    /**
     * Clear password reset token
     */
    public function clearPasswordResetToken(): bool
    {
        return $this->update([
            'password_reset_token' => null,
            'password_reset_expires_at' => null,
        ]);
    }
}
