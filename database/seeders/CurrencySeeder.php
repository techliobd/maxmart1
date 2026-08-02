<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.0,
                'is_default' => true,
                'is_active' => true,
                'decimal_places' => 2,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 0.92,
                'is_default' => false,
                'is_active' => true,
                'decimal_places' => 2,
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => 0.79,
                'is_default' => false,
                'is_active' => true,
                'decimal_places' => 2,
            ],
            [
                'code' => 'CAD',
                'name' => 'Canadian Dollar',
                'symbol' => 'C$',
                'exchange_rate' => 1.36,
                'is_default' => false,
                'is_active' => true,
                'decimal_places' => 2,
            ],
            [
                'code' => 'AUD',
                'name' => 'Australian Dollar',
                'symbol' => 'A$',
                'exchange_rate' => 1.52,
                'is_default' => false,
                'is_active' => true,
                'decimal_places' => 2,
            ],
            [
                'code' => 'JPY',
                'name' => 'Japanese Yen',
                'symbol' => '¥',
                'exchange_rate' => 149.50,
                'is_default' => false,
                'is_active' => true,
                'decimal_places' => 0,
            ],
            [
                'code' => 'BDT',
                'name' => 'Bangladeshi Taka',
                'symbol' => '৳',
                'exchange_rate' => 110.0,
                'is_default' => false,
                'is_active' => true,
                'decimal_places' => 2,
            ],
            [
                'code' => 'INR',
                'name' => 'Indian Rupee',
                'symbol' => '₹',
                'exchange_rate' => 83.0,
                'is_default' => false,
                'is_active' => true,
                'decimal_places' => 2,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }
}
