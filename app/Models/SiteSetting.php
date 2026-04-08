<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function forceDirectUrl($value)
    {
        if (!$value) return $value;
        $value = trim($value);
        
        // Auto-convert Google Drive links
        if (str_contains($value, 'drive.google.com')) {
            // If it's already a direct link or thumbnail, don't re-convert it
            if (str_contains($value, 'drive.google.com/uc') || str_contains($value, 'drive.google.com/thumbnail')) {
                return $value;
            }

            $id = null;
            // Format: /d/FILE_ID/view
            if (preg_match('/\/d\/([a-zA-Z0-9_-]{25,})/', $value, $matches)) {
                $id = $matches[1];
            } 
            // Format: ?id=FILE_ID
            elseif (preg_match('/[?&]id=([a-zA-Z0-9_-]{25,})/', $value, $matches)) {
                $id = $matches[1];
            }
            // Fallback for raw ID strings mixed in
            elseif (preg_match('/[-\w]{25,}/', $value, $matches)) {
                $id = $matches[0];
            }

            if ($id) {
                // thumbnail?id=... is often more reliable than uc?id=... for web display
                return "https://drive.google.com/thumbnail?id=" . $id . "&sz=w1200";
            }
        }
        
        return $value;
    }

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting || !$setting->value) {
            return $default;
        }

        return self::forceDirectUrl($setting->value);
    }
}
