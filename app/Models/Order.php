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
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'transaction_id', 'transaction_id');
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
}