<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'blog_category_id');
    }

    public function publishedPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'blog_category_id')
            ->published();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getSlugAttribute($value): string
    {
        return $value ?? \Illuminate\Support\Str::slug($this->name);
    }

    public function getUrlAttribute(): string
    {
        return route('blog.category.show', ['slug' => $this->slug]);
    }

    public function getPostsCountAttribute(): int
    {
        return $this->publishedPosts()->count();
    }
}
