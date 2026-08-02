<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class BlogPost extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'view_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->where('is_approved', true);
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => strip_tags($this->content),
            'category_name' => $this->category?->name,
        ];
    }

    public function getSlugAttribute($value): string
    {
        return $value ?? \Illuminate\Support\Str::slug($this->title);
    }

    public function getUrlAttribute(): string
    {
        return route('blog.post.show', ['slug' => $this->slug]);
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($words / 200)); // 200 words per minute
    }
}
