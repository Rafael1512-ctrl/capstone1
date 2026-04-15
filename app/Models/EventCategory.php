<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_acara';
    protected $primaryKey = 'category_id';
    public $timestamps = false; // kategori_acara table doesn't have timestamps

    protected $fillable = [
        'name',
    ];


    public function events()
    {
        return $this->hasMany(Event::class, 'category_id', 'category_id');
    }
}
