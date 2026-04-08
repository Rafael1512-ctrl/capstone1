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
            // ... (keep existing logic for drive.google.com)
            if (str_contains($value, 'drive.google.com/uc') || str_contains($value, 'drive.google.com/thumbnail')) {
                return $value;
            }

            $id = null;
            if (preg_match('/\/d\/([a-zA-Z0-9_-]{25,})/', $value, $matches)) {
                $id = $matches[1];
            } elseif (preg_match('/[?&]id=([a-zA-Z0-9_-]{25,})/', $value, $matches)) {
                $id = $matches[1];
            }
            
            if ($id) {
                return "https://drive.google.com/thumbnail?id=" . $id . "&sz=w1200";
            }
        }
        
        // Fallback for raw ID strings (ONLY if they don't look like local file paths)
        // GDrive IDs are typically 33 characters, alphanumeric, with - or _
        // We exclude strings with dots (extensions) or many slashes (paths)
        if (!str_contains($value, '.') && !str_contains($value, '/') && preg_match('/^[a-zA-Z0-9_-]{25,40}$/', $value)) {
            return "https://drive.google.com/thumbnail?id=" . $value . "&sz=w1200";
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
