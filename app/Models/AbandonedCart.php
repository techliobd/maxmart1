<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbandonedCart extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'items' => 'array',
        'recovery_sent' => 'boolean',
        'converted_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnrecovered($query)
    {
        return $query->whereNull('converted_at');
    }

    public function scopePendingRecovery($query)
    {
        return $query->unrecovered()
            ->where('recovery_sent', false)
            ->where('created_at', '<=', now()->subHours(1)); // Send after 1 hour
    }

    public function markAsConverted(): void
    {
        $this->update(['converted_at' => now()]);
    }

    public function markAsSent(): void
    {
        $this->update([
            'recovery_sent' => true,
            'sent_at' => now(),
        ]);
    }

    public function getItemsCountAttribute(): int
    {
        return count($this->items ?? []);
    }

    public function getTotalAmountAttribute(): float
    {
        $total = 0;
        foreach ($this->items ?? [] as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        return round($total, 2);
    }

    public function isRecent(): bool
    {
        return $this->created_at > now()->subDays(7);
    }
}
