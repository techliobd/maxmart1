<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Loggable;

class CmsPage extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'is_homepage' => 'boolean',
        'show_in_footer' => 'boolean',
        'show_in_menu' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFooter($query)
    {
        return $query->where('show_in_footer', true)->published();
    }

    public function scopeMenu($query)
    {
        return $query->where('show_in_menu', true)->published();
    }

    public function getSlugAttribute($value): string
    {
        return $value ?? \Illuminate\Support\Str::slug($this->title);
    }

    public function getUrlAttribute(): string
    {
        if ($this->is_homepage) {
            return route('home');
        }
        return route('page.show', ['slug' => $this->slug]);
    }

    public function isHomepage(): bool
    {
        return $this->is_homepage;
    }
}
