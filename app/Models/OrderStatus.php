<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'notified' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getLabelAttribute(): string
    {
        return Order::getStatuses()[$this->status] ?? ucfirst($this->status);
    }

    public function isDelivered(): bool
    {
        return $this->status === Order::STATUS_DELIVERED;
    }

    public function isCancelled(): bool
    {
        return $this->status === Order::STATUS_CANCELLED;
    }
}
