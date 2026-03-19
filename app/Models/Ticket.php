<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'ticket_type_id', 'unique_code', 
        'qr_code_url', 'is_used', 'used_at'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id');
    }

    public function event()
    {
        return $this->ticketType->event(); // Access event through ticket type
    }

    public function isActive()
    {
        return !$this->is_used;
    }

    public function validate($validatedBy = null)
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }
}