<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'label',
        'first_name',
        'last_name',
        'company',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'is_default_shipping',
        'is_default_billing',
    ];

    protected function casts(): array
    {
        return [
            'is_default_shipping' => 'boolean',
            'is_default_billing' => 'boolean',
        ];
    }

    /**
     * Get the customer this address belongs to.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the formatted full address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->countryName,
        ];

        return implode(', ', array_filter($parts));
    }

    /**
     * Get the country name from code.
     */
    public function getCountryNameAttribute(): string
    {
        $countries = [
            'BD' => 'Bangladesh',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'IN' => 'India',
            'PK' => 'Pakistan',
            'SA' => 'Saudi Arabia',
            'AE' => 'United Arab Emirates',
        ];

        return $countries[$this->country] ?? $this->country;
    }

    /**
     * Set as default shipping address (and unset others).
     */
    public function setAsDefaultShipping(): void
    {
        static::where('customer_id', $this->customer_id)
            ->update(['is_default_shipping' => false]);

        $this->update(['is_default_shipping' => true]);
    }

    /**
     * Set as default billing address (and unset others).
     */
    public function setAsDefaultBilling(): void
    {
        static::where('customer_id', $this->customer_id)
            ->update(['is_default_billing' => false]);

        $this->update(['is_default_billing' => true]);
    }
}
