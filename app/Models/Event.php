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
        'name',
        'description',
        'location',
        'schedule_time',
        'ticket_quota',
        'banner_url',
        'status',
    ];

    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    protected $casts = [
        'schedule_time' => 'datetime',
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
        return $this->hasMany(Order::class, 'event_id', 'event_id');
    }
}
