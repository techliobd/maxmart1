<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRule extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rate' => 'decimal:2',
        'priority' => 'integer',
        'is_active' => 'boolean',
        'is_compound' => 'boolean',
    ];

    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('priority');
    }

    public function appliesTo(string $country, ?string $state = null, ?string $zipCode = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->country && $this->country !== $country) {
            return false;
        }

        if ($this->state && $this->state !== $state) {
            return false;
        }

        if ($this->zip_code && $this->zip_code !== $zipCode) {
            return false;
        }

        return true;
    }

    public function calculate(float $amount): float
    {
        return round(($amount * $this->rate) / 100, 2);
    }
}
