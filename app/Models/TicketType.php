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
        'waiting_list_quota',
        'waiting_list_sold',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    /**
     * Relasi event yang menyertakan soft-deleted events.
     * Digunakan di history tiket agar gambar/info event tetap tampil
     * meskipun event sudah dihapus (soft delete) oleh admin.
     */
    public function eventWithTrashed()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id')->withTrashed();
    }

    public function availableStock()
    {
        return $this->quantity_total - $this->quantity_sold;
    }
}