<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogTag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag')->withTimestamps();
    }

    public function getSlugAttribute($value): string
    {
        return $value ?? \Illuminate\Support\Str::slug($this->name);
    }

    public function getUrlAttribute(): string
    {
        return route('blog.tag.show', ['slug' => $this->slug]);
    }

    public function getPostsCountAttribute(): int
    {
        return $this->posts()->published()->count();
    }
}
