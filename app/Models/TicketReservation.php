<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_type_id', 'user_id', 'quantity', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}