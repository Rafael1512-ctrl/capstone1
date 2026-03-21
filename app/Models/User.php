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
    ];

    protected $hidden = [
        'pass',
    ];

    protected $casts = [
        'role_id' => 'integer',
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
}
