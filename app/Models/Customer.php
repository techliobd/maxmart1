<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'date_of_birth',
        'gender',
        'loyalty_points',
        'total_spent',
        'orders_count',
        'last_order_date',
        'is_verified',
        'subscribed_to_newsletter',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'loyalty_points' => 'integer',
            'total_spent' => 'decimal:2',
            'orders_count' => 'integer',
            'last_order_date' => 'datetime',
            'is_verified' => 'boolean',
            'subscribed_to_newsletter' => 'boolean',
        ];
    }

    /**
     * Get the user associated with this customer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the addresses for this customer.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * Get the default shipping address.
     */
    public function defaultShippingAddress(): ?CustomerAddress
    {
        return $this->addresses()->where('is_default_shipping', true)->first()
            ?? $this->addresses()->first();
    }

    /**
     * Get the default billing address.
     */
    public function defaultBillingAddress(): ?CustomerAddress
    {
        return $this->addresses()->where('is_default_billing', true)->first()
            ?? $this->defaultShippingAddress();
    }

    /**
     * Get the orders for this customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the wishlist items for this customer.
     */
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the cart for this customer.
     */
    public function cart(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the reviews for this customer.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the notifications for this customer.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Increment order count and total spent.
     */
    public function recordOrder(Order $order): void
    {
        $this->increment('orders_count');
        $this->increment('total_spent', $order->total);
        $this->update(['last_order_date' => now()]);
    }

    /**
     * Add loyalty points.
     */
    public function addLoyaltyPoints(int $points): void
    {
        $this->increment('loyalty_points', $points);
    }

    /**
     * Spend loyalty points.
     */
    public function spendLoyaltyPoints(int $points): bool
    {
        if ($this->loyalty_points >= $points) {
            $this->decrement('loyalty_points', $points);
            return true;
        }
        return false;
    }
}
