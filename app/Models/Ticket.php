<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'ticket';

    protected $primaryKey = 'ticket_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'qr_code',
        'event_id',
        'ticket_type_id',
        'ticket_status',
        'transaction_id',
        'qr_code_url'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'transaction_id', 'transaction_id');
    }

    public function isActive()
    {
        return $this->ticket_status === 'Active';
    }

    public function validate()
    {
        $this->update(['ticket_status' => 'Used']);
    }
}