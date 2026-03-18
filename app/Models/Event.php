<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'image',
        'brief_description',
        'location',
        'venue_name',
        'event_date',
        'start_time',
        'end_time',
        'total_capacity',
        'status',
        'base_price',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'time',
        'end_time' => 'time',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function ticketCategories(): HasMany
    {
        return $this->hasMany(TicketCategory::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function waitingLists(): HasMany
    {
        return $this->hasMany(WaitingList::class);
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', '!=', 'draft');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())->orderBy('event_date');
    }

    /**
     * Accessors
     */
    public function getTotalSoldAttribute(): int
    {
        return $this->ticketCategories()->sum('sold_tickets');
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->orders()->where('status', 'paid')->sum('total_price');
    }

    public function getAvailableTicketsAttribute(): int
    {
        return $this->ticketCategories()->sum('available_tickets');
    }
}
