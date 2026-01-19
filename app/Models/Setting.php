<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value
     */
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting_{$key}");
        
        return $setting;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("setting_{$setting->key}");
        }
    }
}
