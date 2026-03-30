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
     * Helper to convert GDrive sharing link to direct image link
     */
    private function convertToDirectGDrive($url)
    {
        if (!$url) return $url;
        
        // Check if it's already a direct download/thumbnail link
        if (str_contains($url, 'drive.google.com/uc') || str_contains($url, 'drive.google.com/thumbnail')) {
            return $url;
        }

        if (str_contains($url, 'drive.google.com')) {
            $id = null;
            
            // Format: /d/FILE_ID/view
            if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                $id = $matches[1];
            } 
            // Format: ?id=FILE_ID
            elseif (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
                $id = $matches[1];
            }

            if ($id) {
                // thumbnail?id=... is very reliable for public images
                // h1200 for large background
                return "https://drive.google.com/thumbnail?id=" . $id . "&sz=w1200";
            }
        }
        return $url;
    }

    /**
     * Accessor for background_url to handle GDrive links.
     */
    public function getBackgroundUrlAttribute($value)
    {
        return $this->convertToDirectGDrive($value);
    }

    /**
     * Accessor for image_url to handle GDrive links.
     */
    public function getImageUrlAttribute($value)
    {
        return $this->convertToDirectGDrive($value);
    }
}
