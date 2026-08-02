<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
        'supports_refund' => 'boolean',
        'sort_order' => 'integer',
    ];

    public const CODE_COD = 'cod';
    public const CODE_BANK_TRANSFER = 'bank_transfer';
    public const CODE_STRIPE = 'stripe';
    public const CODE_PAYPAL = 'paypal';
    public const CODE_SSLCOMMERZ = 'sslcommerz';
    public const CODE_BKASH = 'bkash';
    public const CODE_NAGAD = 'nagad';

    public static function getGateways(): array
    {
        return [
            self::CODE_COD => 'Cash on Delivery',
            self::CODE_BANK_TRANSFER => 'Bank Transfer',
            self::CODE_STRIPE => 'Stripe',
            self::CODE_PAYPAL => 'PayPal',
            self::CODE_SSLCOMMERZ => 'SSLCommerz',
            self::CODE_BKASH => 'bKash',
            self::CODE_NAGAD => 'Nagad',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeAvailable($query)
    {
        return $query->active()->where('is_test_mode', false);
    }

    public static function getAvailableGateways()
    {
        return self::available()->get();
    }

    public function setCredentialAttribute(string $key, string $value): void
    {
        $credentials = $this->credentials ?? [];
        $credentials[$key] = Crypt::encryptString($value);
        $this->update(['credentials' => $credentials]);
    }

    public function getCredential(string $key): ?string
    {
        $credentials = $this->credentials ?? [];
        $encrypted = $credentials[$key] ?? null;

        if (!$encrypted) {
            return null;
        }

        try {
            return Crypt::decryptString($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function isCod(): bool
    {
        return $this->code === self::CODE_COD;
    }

    public function isBankTransfer(): bool
    {
        return $this->code === self::CODE_BANK_TRANSFER;
    }

    public function isOnline(): bool
    {
        return !in_array($this->code, [self::CODE_COD, self::CODE_BANK_TRANSFER]);
    }
}
