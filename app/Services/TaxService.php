<?php

namespace App\Services;

use App\Models\TaxClass;
use App\Models\TaxRule;
use App\Models\CustomerAddress;

class TaxService
{
    /**
     * Calculate tax amount for an order
     */
    public function calculateTax(float $subtotal, float $shippingCost, array $address): float
    {
        if (empty($address['country'])) {
            return 0.0;
        }

        $taxRules = $this->getApplicableTaxRules($address);

        if ($taxRules->isEmpty()) {
            return 0.0;
        }

        $totalTax = 0.0;

        foreach ($taxRules as $rule) {
            $taxAmount = $this->calculateRuleTax($rule, $subtotal, $shippingCost);
            $totalTax += $taxAmount;
        }

        return round($totalTax, 2);
    }

    /**
     * Get applicable tax rules for address
     */
    protected function getApplicableTaxRules(array $address): \Illuminate\Database\Eloquent\Collection
    {
        $query = TaxRule::with('taxClass')
            ->where('is_active', true)
            ->where(function ($q) use ($address) {
                // Match country
                $q->where('country', $address['country'])
                    ->orWhereNull('country');
            });

        // Filter by state if provided
        if (!empty($address['state'])) {
            $query->where(function ($q) use ($address) {
                $q->where('state', $address['state'])
                    ->orWhereNull('state');
            });
        }

        // Filter by postal code if provided
        if (!empty($address['postal_code'])) {
            $query->where(function ($q) use ($address) {
                $q->where('postal_code', $address['postal_code'])
                    ->orWhereNull('postal_code');
            });
        }

        return $query->get();
    }

    /**
     * Calculate tax for a specific rule
     */
    protected function calculateRuleTax(TaxRule $rule, float $subtotal, float $shippingCost): float
    {
        $taxableAmount = 0.0;

        // Determine what to tax based on rule settings
        if ($rule->apply_to_products) {
            $taxableAmount += $subtotal;
        }

        if ($rule->apply_to_shipping) {
            $taxableAmount += $shippingCost;
        }

        // Check minimum order threshold
        if ($rule->minimum_order_amount && $subtotal < $rule->minimum_order_amount) {
            return 0.0;
        }

        // Calculate tax based on type
        switch ($rule->calculation_type) {
            case 'fixed':
                return $rule->rate;
            
            case 'percentage':
                $taxAmount = $taxableAmount * ($rule->rate / 100);
                
                // Apply priority if multiple rules
                if ($rule->priority > 1) {
                    $taxAmount = $taxAmount / $rule->priority;
                }
                
                return $taxAmount;
            
            case 'compound':
                // Compound tax is calculated on subtotal + other taxes
                return $taxableAmount * ($rule->rate / 100);
            
            default:
                return 0.0;
        }
    }

    /**
     * Calculate tax for a single product
     */
    public function calculateProductTax(float $price, int $taxClassId, array $address): float
    {
        $taxClass = TaxClass::find($taxClassId);

        if (!$taxClass || !$taxClass->is_active) {
            return 0.0;
        }

        $taxRules = $this->getApplicableTaxRules($address)
            ->where('tax_class_id', $taxClassId);

        if ($taxRules->isEmpty()) {
            // Use default tax class rate if available
            $defaultRate = config('tax.default_rate', 0);
            return round($price * ($defaultRate / 100), 2);
        }

        $totalTax = 0.0;

        foreach ($taxRules as $rule) {
            if ($rule->calculation_type === 'percentage') {
                $totalTax += $price * ($rule->rate / 100);
            } elseif ($rule->calculation_type === 'fixed') {
                $totalTax += $rule->rate;
            }
        }

        return round($totalTax, 2);
    }

    /**
     * Get tax breakdown for display
     */
    public function getTaxBreakdown(float $subtotal, float $shippingCost, array $address): array
    {
        $taxRules = $this->getApplicableTaxRules($address);
        $breakdown = [];

        foreach ($taxRules as $rule) {
            $taxAmount = $this->calculateRuleTax($rule, $subtotal, $shippingCost);

            if ($taxAmount > 0) {
                $breakdown[] = [
                    'name' => $rule->name,
                    'rate' => $rule->rate,
                    'type' => $rule->calculation_type,
                    'amount' => round($taxAmount, 2),
                    'priority' => $rule->priority,
                ];
            }
        }

        return $breakdown;
    }

    /**
     * Check if address is in a tax-free zone
     */
    public function isTaxFreeZone(array $address): bool
    {
        $taxFreeZones = config('tax.free_zones', []);

        foreach ($taxFreeZones as $zone) {
            if ($zone['country'] === $address['country']) {
                if (empty($zone['states'])) {
                    return true;
                }

                if (!empty($address['state']) && in_array($address['state'], $zone['states'])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Validate tax number (VAT/GST)
     */
    public function validateTaxNumber(string $taxNumber, string $countryCode): bool
    {
        $taxNumber = strtoupper(trim($taxNumber));

        // Basic validation patterns by country
        $patterns = [
            'US' => '/^\d{2}-\d{7}$/', // EIN format
            'GB' => '/^[A-Z]{2}\d{6}[A-D]$/', // UK VAT
            'DE' => '/^\d{9}$/', // German VAT
            'FR' => '/^[A-Z0-9]{2}\d{9}$/', // French VAT
            'BD' => '/^\d{10}$/', // Bangladesh BIN
            'IN' => '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', // Indian GST
        ];

        if (isset($patterns[$countryCode])) {
            return (bool) preg_match($patterns[$countryCode], $taxNumber);
        }

        // Generic validation - at least 6 characters
        return strlen($taxNumber) >= 6;
    }

    /**
     * Get tax class by name
     */
    public function getTaxClassByName(string $name): ?TaxClass
    {
        return TaxClass::where('name', $name)->where('is_active', true)->first();
    }

    /**
     * Calculate total with tax included (reverse calculation)
     */
    public function getPreTaxAmount(float $totalWithTax, float $taxRate): float
    {
        if ($taxRate <= 0) {
            return $totalWithTax;
        }

        return round($totalWithTax / (1 + ($taxRate / 100)), 2);
    }

    /**
     * Format tax amount for display
     */
    public function formatTaxAmount(float $amount, string $currencyCode = null): string
    {
        $currency = $currencyCode ?? config('app.currency', 'USD');
        
        return number_format($amount, 2) . ' ' . $currency;
    }

    /**
     * Check if customer is eligible for tax exemption
     */
    public function isCustomerTaxExempt(?int $customerId): bool
    {
        if (!$customerId) {
            return false;
        }

        $customer = \App\Models\Customer::find($customerId);

        if (!$customer) {
            return false;
        }

        return $customer->is_tax_exempt && 
               !empty($customer->tax_exemption_number) &&
               $customer->tax_exemption_valid_until &&
               $customer->tax_exemption_valid_until->isFuture();
    }

    /**
     * Get all active tax classes
     */
    public function getActiveTaxClasses(): \Illuminate\Database\Eloquent\Collection
    {
        return TaxClass::where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Sync product tax class with tax rules
     */
    public function syncProductTaxClass(int $productId, ?int $taxClassId): void
    {
        $product = \App\Models\Product::findOrFail($productId);
        $product->update(['tax_class_id' => $taxClassId]);

        // Update variations if they inherit tax class
        $product->variations()->update(['tax_class_id' => $taxClassId]);
    }
}
