<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'name', 'description', 'price', 
        'quantity_total', 'quantity_sold'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function waitingLists()
    {
        return $this->hasMany(WaitingList::class);
    }

    public function reservations()
    {
        return $this->hasMany(TicketReservation::class);
    }

    // Hitung stok tersedia
    public function availableStock()
    {
        return $this->quantity_total - $this->quantity_sold;
    }
}