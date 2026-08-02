<?php

namespace App\Services;

use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Collection;

class ShippingService
{
    /**
     * Calculate shipping cost for an order
     */
    public function calculateShipping(array $address, Collection $items): float
    {
        if (empty($address['country'])) {
            return 0.0;
        }

        $shippingZone = $this->findMatchingZone($address);

        if (!$shippingZone) {
            return $this->getDefaultShippingCost($items);
        }

        return $this->calculateZoneShipping($shippingZone, $address, $items);
    }

    /**
     * Find matching shipping zone for address
     */
    protected function findMatchingZone(array $address): ?ShippingZone
    {
        $zones = ShippingZone::with(['rates', 'countries'])->where('is_active', true)->get();

        foreach ($zones as $zone) {
            // Check if country matches
            if ($zone->countries()->where('country_code', $address['country'])->exists()) {
                // Check state/province restriction if applicable
                if (!empty($zone->states) && !empty($address['state'])) {
                    $allowedStates = is_array($zone->states) ? $zone->states : json_decode($zone->states, true);
                    if (!in_array($address['state'], $allowedStates)) {
                        continue;
                    }
                }

                // Check postal code restriction if applicable
                if (!empty($zone->postal_codes)) {
                    $postalCodes = is_array($zone->postal_codes) ? $zone->postal_codes : json_decode($zone->postal_codes, true);
                    $matched = false;
                    
                    foreach ($postalCodes as $pattern) {
                        if ($this->matchPostalCode($address['postal_code'] ?? '', $pattern)) {
                            $matched = true;
                            break;
                        }
                    }
                    
                    if (!$matched) {
                        continue;
                    }
                }

                return $zone;
            }
        }

        return null;
    }

    /**
     * Match postal code against pattern (supports wildcards)
     */
    protected function matchPostalCode(string $postalCode, string $pattern): bool
    {
        // Convert wildcard pattern to regex
        $regex = str_replace('*', '.*', preg_quote($pattern, '/'));
        $regex = '/^' . $regex . '$/i';
        
        return (bool) preg_match($regex, $postalCode);
    }

    /**
     * Calculate shipping cost for a specific zone
     */
    protected function calculateZoneShipping(ShippingZone $zone, array $address, Collection $items): float
    {
        $totalWeight = $items->sum(fn($item) => $item->product->weight * $item->quantity);
        $subtotal = $items->sum(fn($item) => $item->unit_price * $item->quantity);
        $totalItems = $items->sum('quantity');

        $cheapestRate = null;
        $cheapestCost = PHP_FLOAT_MAX;

        foreach ($zone->rates as $rate) {
            $cost = $this->calculateRateCost($rate, $totalWeight, $subtotal, $totalItems);

            if ($cost < $cheapestCost) {
                $cheapestCost = $cost;
                $cheapestRate = $rate;
            }
        }

        return $cheapestRate ? round($cheapestCost, 2) : 0.0;
    }

    /**
     * Calculate cost for a specific shipping rate
     */
    protected function calculateRateCost(ShippingRate $rate, float $totalWeight, float $subtotal, int $totalItems): float
    {
        // Check if rate conditions are met
        if ($rate->min_weight && $totalWeight < $rate->min_weight) {
            return PHP_FLOAT_MAX;
        }
        if ($rate->max_weight && $totalWeight > $rate->max_weight) {
            return PHP_FLOAT_MAX;
        }
        if ($rate->min_order_total && $subtotal < $rate->min_order_total) {
            return PHP_FLOAT_MAX;
        }
        if ($rate->max_order_total && $subtotal > $rate->max_order_total) {
            return PHP_FLOAT_MAX;
        }

        $cost = $rate->base_cost;

        // Add weight-based cost
        if ($rate->per_kg_rate && $totalWeight > 0) {
            $chargeableWeight = max($rate->min_chargeable_weight ?? 0, $totalWeight);
            $cost += $chargeableWeight * $rate->per_kg_rate;
        }

        // Add per-item cost
        if ($rate->per_item_rate && $totalItems > 0) {
            $cost += $totalItems * $rate->per_item_rate;
        }

        // Apply percentage of order total if configured
        if ($rate->percentage_of_order && $subtotal > 0) {
            $cost += ($subtotal * $rate->percentage_of_order / 100);
        }

        // Apply free shipping threshold
        if ($rate->free_shipping_above && $subtotal >= $rate->free_shipping_above) {
            return 0.0;
        }

        return max(0, $cost);
    }

    /**
     * Get default shipping cost when no zone matches
     */
    protected function getDefaultShippingCost(Collection $items): float
    {
        $defaultRate = config('shipping.default_rate', 10.00);
        $totalWeight = $items->sum(fn($item) => $item->product->weight * $item->quantity);
        
        return round($defaultRate + ($totalWeight * config('shipping.per_kg_rate', 0)), 2);
    }

    /**
     * Get available shipping methods for address
     */
    public function getAvailableMethods(array $address, Collection $items): array
    {
        $shippingZone = $this->findMatchingZone($address);

        if (!$shippingZone) {
            return [
                [
                    'id' => 'default',
                    'name' => 'Standard Shipping',
                    'cost' => $this->getDefaultShippingCost($items),
                    'estimated_days' => '5-7',
                ],
            ];
        }

        $methods = [];
        $totalWeight = $items->sum(fn($item) => $item->product->weight * $item->quantity);
        $subtotal = $items->sum(fn($item) => $item->unit_price * $item->quantity);
        $totalItems = $items->sum('quantity');

        foreach ($shippingZone->rates as $rate) {
            $cost = $this->calculateRateCost($rate, $totalWeight, $subtotal, $totalItems);

            if ($cost < PHP_FLOAT_MAX) {
                $methods[] = [
                    'id' => $rate->id,
                    'name' => $rate->name,
                    'description' => $rate->description,
                    'cost' => round($cost, 2),
                    'estimated_days' => $rate->estimated_delivery_days ?? '3-5',
                    'is_free' => $cost === 0,
                ];
            }
        }

        // Sort by cost
        usort($methods, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return $methods;
    }

    /**
     * Check if free shipping is available for address and cart
     */
    public function hasFreeShipping(array $address, Collection $items): bool
    {
        $methods = $this->getAvailableMethods($address, $items);
        
        foreach ($methods as $method) {
            if ($method['is_free']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get estimated delivery date range
     */
    public function getEstimatedDelivery(int $shippingRateId, ?string $orderDate = null): array
    {
        $rate = ShippingRate::find($shippingRateId);
        
        if (!$rate) {
            return ['min' => null, 'max' => null];
        }

        $baseDate = $orderDate ? \Carbon\Carbon::parse($orderDate) : now();
        
        // Add processing time (1-2 business days)
        $baseDate->addDays(2);

        $minDays = $rate->estimated_delivery_days ? explode('-', $rate->estimated_delivery_days)[0] : 3;
        $maxDays = $rate->estimated_delivery_days ? explode('-', $rate->estimated_delivery_days)[1] : 5;

        $minDate = (clone $baseDate)->addDays((int)$minDays);
        $maxDate = (clone $baseDate)->addDays((int)$maxDays);

        return [
            'min' => $minDate->format('Y-m-d'),
            'max' => $maxDate->format('Y-m-d'),
            'min_formatted' => $minDate->format('M j, Y'),
            'max_formatted' => $maxDate->format('M j, Y'),
        ];
    }

    /**
     * Validate shipping address
     */
    public function validateAddress(array $address): array
    {
        $errors = [];

        if (empty($address['first_name'])) {
            $errors[] = 'First name is required';
        }
        if (empty($address['last_name'])) {
            $errors[] = 'Last name is required';
        }
        if (empty($address['address_line_1'])) {
            $errors[] = 'Address is required';
        }
        if (empty($address['city'])) {
            $errors[] = 'City is required';
        }
        if (empty($address['postal_code'])) {
            $errors[] = 'Postal code is required';
        }
        if (empty($address['country'])) {
            $errors[] = 'Country is required';
        }
        if (empty($address['phone'])) {
            $errors[] = 'Phone number is required';
        }

        return ['valid' => empty($errors), 'errors' => $errors];
    }

    /**
     * Format address for display
     */
    public function formatAddress(array|CustomerAddress $address): string
    {
        if ($address instanceof CustomerAddress) {
            $address = [
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'country' => $address->country,
            ];
        }

        $parts = [
            $address['address_line_1'] ?? '',
            $address['address_line_2'] ?? '',
            $address['city'] ?? '',
            $address['state'] ?? '',
            $address['postal_code'] ?? '',
            $address['country'] ?? '',
        ];

        return implode(', ', array_filter($parts));
    }
}
