<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_answered' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeAnswered($query)
    {
        return $query->where('is_answered', true);
    }

    public function scopeUnanswered($query)
    {
        return $query->where('is_answered', false);
    }

    public function markAsAnswered(string $answer, int $userId): void
    {
        $this->update([
            'answer' => $answer,
            'answered_by' => $userId,
            'answered_at' => now(),
            'is_answered' => true,
        ]);
    }
}
