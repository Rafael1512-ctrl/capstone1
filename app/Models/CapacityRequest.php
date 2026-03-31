<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapacityRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'ticket_type_id',
        'current_capacity',
        'requested_capacity',
        'reason',
        'status',
        'admin_notes'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id', 'id');
    }
}
