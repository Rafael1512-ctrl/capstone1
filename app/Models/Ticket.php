<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'event_id',
        'ticket_category_id',
        'ticket_number',
        'qr_code',
        'qr_code_path',
        'status',
        'used_at',
        'validated_at',
        'validated_by',
        'notes',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'validated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->ticket_number = 'TKT-' . date('Ymd') . '-' . strtoupper(uniqid());
        });
    }

    /**
     * Relations
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketCategory(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    /**
     * Methods
     */
    public function markAsUsed(): void
    {
        $this->update([
            'status' => 'used',
            'used_at' => now(),
        ]);
    }

    public function validate(string $validatedBy): void
    {
        $this->update([
            'status' => 'used',
            'validated_at' => now(),
            'validated_by' => $validatedBy,
        ]);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isUsed(): bool
    {
        return $this->status === 'used';
    }
}
