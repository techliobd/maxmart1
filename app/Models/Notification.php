<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public const TYPE_ORDER = 'order';
    public const TYPE_PRODUCT = 'product';
    public const TYPE_PROMOTION = 'promotion';
    public const TYPE_SYSTEM = 'system';
    public const TYPE_REVIEW = 'review';
    public const TYPE_QUESTION = 'question';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_ORDER => 'shopping-bag',
            self::TYPE_PRODUCT => 'package',
            self::TYPE_PROMOTION => 'tag',
            self::TYPE_SYSTEM => 'bell',
            self::TYPE_REVIEW => 'star',
            self::TYPE_QUESTION => 'message-circle',
            default => 'bell',
        };
    }

    public function getColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_ORDER => 'blue',
            self::TYPE_PRODUCT => 'green',
            self::TYPE_PROMOTION => 'purple',
            self::TYPE_SYSTEM => 'gray',
            self::TYPE_REVIEW => 'yellow',
            self::TYPE_QUESTION => 'indigo',
            default => 'gray',
        };
    }
}
