<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'flag_icon',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the default language.
     */
    public static function getDefault(): self
    {
        return cache()->remember('default_language', 3600, function () {
            return static::where('is_default', true)
                ->where('is_active', true)
                ->first()
                ?? static::first();
        });
    }

    /**
     * Get all active languages.
     */
    public static function getActive(): array
    {
        return cache()->remember('active_languages', 3600, function () {
            return static::where('is_active', true)->get();
        });
    }

    /**
     * Scope for active languages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
