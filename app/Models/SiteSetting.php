<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group', 'type', 'label', 'hint', 'display_order'];

    protected static function booted(): void
    {
        static::saved(fn () => self::flush());
        static::deleted(fn () => self::flush());
    }

    public static function flush(): void
    {
        Cache::forget('site-settings.all');
    }

    /** @return array<string, string> */
    public static function map(): array
    {
        return Cache::rememberForever('site-settings.all', fn () => self::query()
            ->orderBy('display_order')
            ->orderBy('id')
            ->pluck('value', 'key')
            ->all());
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = self::map()[$key] ?? null;

        return $value !== null && $value !== '' ? $value : $default;
    }

    public static function put(string $key, ?string $value): void
    {
        $setting = self::query()->where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            self::query()->create(['key' => $key, 'value' => $value, 'label' => str($key)->headline()->toString()]);
        }
    }
}
