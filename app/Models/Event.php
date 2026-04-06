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
        'maps_url',
        'batch1_start_at',
        'batch1_regular_quota', 'batch1_regular_price', 'batch1_regular_sold',
        'batch1_vip_quota', 'batch1_vip_price', 'batch1_vip_sold',
        'batch1_vvip_quota', 'batch1_vvip_price', 'batch1_vvip_sold',
        'batch2_start_at',
        'batch2_regular_quota', 'batch2_regular_price', 'batch2_regular_sold',
        'batch2_vip_quota', 'batch2_vip_price', 'batch2_vip_sold',
        'batch2_vvip_quota', 'batch2_vvip_price', 'batch2_vvip_sold',
        'batch1_ended_at', 'batch2_ended_at',
    ];

    protected $casts = [
        'schedule_time' => 'datetime',
        'performers' => 'array',
        'batch1_start_at' => 'datetime',
        'batch2_start_at' => 'datetime',
        'batch1_ended_at' => 'datetime',
        'batch2_ended_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active (published and NOT overdue) events.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['published', 'Active'])
                     ->where('schedule_time', '>=', now());
    }

    /**
     * Check if the event is "Active" based on timing.
     */
    public function getIsActiveAttribute()
    {
        if (!$this->batch1_start_at) return false;
        return now()->isAfter($this->batch1_start_at);
    }

    /**
     * Determine which batch is currently active.
     */
    public function getActiveBatchAttribute()
    {
        if (!$this->batch1_start_at) return null;

        $now = now();

        // Batch 2 active if started AND not ended
        if ($this->batch2_start_at && $now->isAfter($this->batch2_start_at)) {
            if (!$this->batch2_ended_at || $this->batch2_ended_at->isFuture()) {
                return 2;
            }
            return null; // Batch 2 is already ended
        }

        // Batch 1 active if started AND not ended
        if ($now->isAfter($this->batch1_start_at)) {
            if (!$this->batch1_ended_at || $this->batch1_ended_at->isFuture()) {
                return 1;
            }
            // If Batch 1 is ended, but Batch 2 hasn't started yet, return null (waiting period)
            return null; 
        }

        return null;
    }

    /**
     * Update status of events that have passed their schedule time to 'overdue'.
     */
    public static function updateOverdueEvents()
    {
        // Set to overdue if event time passed
        self::where('status', '!=', 'overdue')
            ->whereNotNull('schedule_time')
            ->where('schedule_time', '<', now())
            ->update(['status' => 'overdue']);

        // Set to Active if Batch 1 time reached
        self::where('status', 'Non-Active')
            ->whereNotNull('batch1_start_at')
            ->where('batch1_start_at', '<=', now())
            ->update(['status' => 'Active']);
    }

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
            'event_id',       // Foreign key on tickets table
            'transaction_id', // Foreign key on orders table (transaksi)
            'event_id',       // Local key on events table
            'transaction_id'  // Local key on tickets table
        );
    }
}
