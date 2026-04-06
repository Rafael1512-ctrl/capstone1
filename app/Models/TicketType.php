<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $table = 'ticket_type';
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quantity_total',
        'quantity_sold',
        'batch_number',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function availableStock()
    {
        return $this->quantity_total - $this->quantity_sold;
    }
}