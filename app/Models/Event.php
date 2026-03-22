<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'image',
    ];

    protected $casts = [
        'date' => 'date',
    ];
<<<<<<< Updated upstream
=======

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id', 'category_id');
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class, 'event_id', 'event_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id', 'event_id');
    }

    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            Ticket::class,
            'event_id', // Foreign key on tickets table...
            'transaction_id', // Foreign key on transactions table...
            'event_id', // Local key on events table...
            'transaction_id' // Local key on tickets table...
        );
    }
>>>>>>> Stashed changes
}
