<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'badge_text',
        'image_url',
        'background_url',
        'link_url',
        'button_text',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accessor for background_url to handle GDrive links.
     */
    public function getBackgroundUrlAttribute($value)
    {
        return \App\Models\SiteSetting::forceDirectUrl($value);
    }
    /**
     * Accessor for image_url to handle GDrive links.
     */
    public function getImageUrlAttribute($value)
    {
        return \App\Models\SiteSetting::forceDirectUrl($value);
    }
}
