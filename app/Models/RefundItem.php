<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RefundItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'reason' => 'string',
    ];

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

    public function refundable(): MorphTo
    {
        return $this->morphTo();
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'refundable_id')
            ->where('refundable_type', OrderItem::class);
    }
}
