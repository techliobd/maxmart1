<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Vote extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'vote' => 'integer', // 1 for helpful/upvote, 0 for not helpful/downvote
    ];

    public function votable(): MorphTo
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

    public function scopeUpvote($query)
    {
        return $query->where('vote', 1);
    }

    public function scopeDownvote($query)
    {
        return $query->where('vote', 0);
    }

    public function isUpvote(): bool
    {
        return $this->vote === 1;
    }

    public function isDownvote(): bool
    {
        return $this->vote === 0;
    }
}
