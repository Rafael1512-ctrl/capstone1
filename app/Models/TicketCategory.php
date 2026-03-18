<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'total_tickets',
        'available_tickets',
        'sold_tickets',
        'queue_count',
        'status',
    ];

    /**
     * Relations
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
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
    public function scopeAvailable($query)
    {
        return $query->where('available_tickets', '>', 0)->where('status', 'active');
    }

    /**
     * Methods
     */
    public function decreaseAvailableTickets(int $quantity): void
    {
        $this->decrement('available_tickets', $quantity);
        $this->increment('sold_tickets', $quantity);

        if ($this->available_tickets <= 0) {
            $this->update(['status' => 'sold_out']);
        }
    }

    public function increaseAvailableTickets(int $quantity): void
    {
        $this->increment('available_tickets', $quantity);
        if ($this->available_tickets > 0) {
            $this->update(['status' => 'active']);
        }
    }

    public function getSoldPercentage(): float
    {
        return ($this->sold_tickets / $this->total_tickets) * 100;
    }
}
