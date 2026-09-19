<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AttendanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Get a setting value by key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            return $setting->castValue();
        }

        return $default;
    }

    /**
     * Set a setting value by key (upsert).
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );

        static::clearCache($key);
    }

    /**
     * Get all settings as key-value collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAll(): Collection
    {
        return static::pluck('value', 'key');
    }

    /**
     * Set multiple settings at once.
     *
     * @param  array  $settings  [key => value, ...]
     * @return void
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        static::clearCache();
    }

    /**
     * Delete a setting by key (fallback to config default).
     *
     * @param  string  $key
     * @return void
     */
    public static function deleteByKey(string $key): void
    {
        static::where('key', $key)->delete();
        static::clearCache($key);
    }

    /**
     * Delete multiple settings at once.
     *
     * @param  array  $keys
     * @return void
     */
    public static function deleteMany(array $keys): void
    {
        static::whereIn('key', $keys)->delete();
        static::clearCache();
    }

    /**
     * Clear cache for a specific key or all attendance config cache.
     *
     * @param  string|null  $key
     * @return void
     */
    public static function clearCache(?string $key = null): void
    {
        if ($key) {
            Cache::forget("attendance_config:{$key}");
        } else {
            // Clear all attendance config cache entries
            $keys = static::pluck('key')->toArray();
            foreach ($keys as $k) {
                Cache::forget("attendance_config:{$k}");
            }
        }
    }

    /**
     * Cast value based on type column.
     *
     * @return mixed
     */
    public function castValue(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'array'   => json_decode($this->value, true) ?? [],
            default   => $this->value,
        };
    }
}
