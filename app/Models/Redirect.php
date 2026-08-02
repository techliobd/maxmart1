<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_permanent' => 'boolean',
        'hit_count' => 'integer',
        'last_hit_at' => 'datetime',
    ];

    public const TYPE_301 = 301; // Permanent
    public const TYPE_302 = 302; // Temporary

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePermanent($query)
    {
        return $query->where('is_permanent', true);
    }

    public function scopeTemporary($query)
    {
        return $query->where('is_permanent', false);
    }

    public function getStatusCodeAttribute(): int
    {
        return $this->is_permanent ? self::TYPE_301 : self::TYPE_302;
    }

    public function trackHit(): void
    {
        $this->increment('hit_count');
        $this->update(['last_hit_at' => now()]);
    }

    public function normalizeUrl(string $url): string
    {
        $url = trim($url);
        
        // Add leading slash if missing
        if (!str_starts_with($url, '/') && !str_starts_with($url, 'http')) {
            $url = '/' . $url;
        }
        
        // Remove trailing slashes (except for root)
        if ($url !== '/' && str_ends_with($url, '/')) {
            $url = rtrim($url, '/');
        }
        
        return $url;
    }

    public static function findRedirect(string $fromUrl): ?self
    {
        $normalized = (new self)->normalizeUrl($fromUrl);
        
        return self::active()
            ->where('from_url', $normalized)
            ->orWhere('from_url', ltrim($normalized, '/'))
            ->first();
    }
}
