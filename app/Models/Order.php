<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $primaryKey = 'transaction_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Based on your db_tixly structure

    protected $fillable = [
        'transaction_id',
        'user_id',
        'ticket_id',
        'payment_date',
        'payment_method',
        'payment_status',
        'total_ticket',
        'total_amount',
        'expires_at',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'transaction_id', 'transaction_id');
    }

    public function event()
    {
        return $this->hasOneThrough(
            Event::class,
            Ticket::class,
            'transaction_id', // Foreign key on tickets table...
            'event_id',      // Foreign key on acara table...
            'transaction_id', // Local key on transactions table...
            'event_id'       // Local key on tickets table...
        );
    }

    // Helper methods for controller logic
    public function isPaid()
    {
        return $this->payment_status === 'Verified';
    }

    public function isPending()
    {
        return $this->payment_status === 'Pending';
    }

    public function isExpired()
    {
        return $this->isPending() && $this->expires_at && $this->expires_at->isPast();
    }
}