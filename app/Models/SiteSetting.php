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
            if (preg_match('/[-\w]{25,}/', $value, $matches)) {
                return "https://drive.google.com/thumbnail?id=" . $matches[0] . "&sz=w1000";
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
