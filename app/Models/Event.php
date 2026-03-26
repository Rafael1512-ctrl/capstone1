<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'acara';

    protected $primaryKey = 'event_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'organizer_id',
        'category_id',
        'title',
        'description',
        'location',
        'schedule_time',
        'ticket_quota',
        'banner_url',
        'status',
        'performers',
    ];

    protected $casts = [
        'schedule_time' => 'datetime',
        'performers' => 'array',
    ];

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
            'event_id', // Foreign key on tickets table
            'ticket_id', // Foreign key on orders table
            'event_id', // Local key on events table
            'ticket_id' // Local key on tickets table
        );
    }
}
