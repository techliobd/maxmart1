<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Scout\Searchable;

class Review extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function helpfulVotes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'comment' => $this->comment,
            'rating' => $this->rating,
            'customer_name' => $this->customer?->full_name,
        ];
    }

    public function isVerifiedPurchase(): bool
    {
        return $this->is_verified && $this->order_id !== null;
    }

    public function getHelpfulCountAttribute(): int
    {
        return $this->helpfulVotes()->where('vote', 1)->count();
    }

    public function getNotHelpfulCountAttribute(): int
    {
        return $this->helpfulVotes()->where('vote', 0)->count();
    }
}
