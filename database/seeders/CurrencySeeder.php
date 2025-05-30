<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $currencies = [
            // Southern Africa
            ['code' => 'ZAR', 'country' => 'South Africa', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BWP', 'country' => 'Botswana', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'LSL', 'country' => 'Lesotho', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'NAD', 'country' => 'Namibia', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SZL', 'country' => 'Eswatini', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MZN', 'country' => 'Mozambique', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MWK', 'country' => 'Malawi', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'ZMW', 'country' => 'Zambia', 'created_at' => $now, 'updated_at' => $now],

            // East Africa
            ['code' => 'KES', 'country' => 'Kenya', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'TZS', 'country' => 'Tanzania', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'UGX', 'country' => 'Uganda', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'RWF', 'country' => 'Rwanda', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BIF', 'country' => 'Burundi', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'DJF', 'country' => 'Djibouti', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SOS', 'country' => 'Somalia', 'created_at' => $now, 'updated_at' => $now],

            // West Africa
            ['code' => 'NGN', 'country' => 'Nigeria', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'GHS', 'country' => 'Ghana', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'XOF', 'country' => 'West African States (UEMOA)', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SLL', 'country' => 'Sierra Leone', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'LRD', 'country' => 'Liberia', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'GMD', 'country' => 'Gambia', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'GNF', 'country' => 'Guinea', 'created_at' => $now, 'updated_at' => $now],

            // Central Africa
            ['code' => 'XAF', 'country' => 'Central African States (CEMAC)', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'CDF', 'country' => 'Democratic Republic of Congo', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'AOA', 'country' => 'Angola', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'STD', 'country' => 'São Tomé and Príncipe', 'created_at' => $now, 'updated_at' => $now],

            // North Africa
            ['code' => 'DZD', 'country' => 'Algeria', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MAD', 'country' => 'Morocco', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'TND', 'country' => 'Tunisia', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'EGP', 'country' => 'Egypt', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SDG', 'country' => 'Sudan', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'LYD', 'country' => 'Libya', 'created_at' => $now, 'updated_at' => $now],

            // Island nations
            ['code' => 'SCR', 'country' => 'Seychelles', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MUR', 'country' => 'Mauritius', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'KMF', 'country' => 'Comoros', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MGA', 'country' => 'Madagascar', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'ZWL', 'country' => 'Zimbabwe', 'created_at' => $now, 'updated_at' => $now],

            // International currencies
            ['code' => 'USD', 'country' => 'United States', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'EUR', 'country' => 'Eurozone', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'GBP', 'country' => 'United Kingdom', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'JPY', 'country' => 'Japan', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'CNY', 'country' => 'China', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'CAD', 'country' => 'Canada', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'AUD', 'country' => 'Australia', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'NZD', 'country' => 'New Zealand', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'CHF', 'country' => 'Switzerland', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'INR', 'country' => 'India', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'RUB', 'country' => 'Russia', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'BRL', 'country' => 'Brazil', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MXN', 'country' => 'Mexico', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SEK', 'country' => 'Sweden', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'NOK', 'country' => 'Norway', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'DKK', 'country' => 'Denmark', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SGD', 'country' => 'Singapore', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'HKD', 'country' => 'Hong Kong', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'KRW', 'country' => 'South Korea', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'MYR', 'country' => 'Malaysia', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'THB', 'country' => 'Thailand', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'IDR', 'country' => 'Indonesia', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'PHP', 'country' => 'Philippines', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'AED', 'country' => 'United Arab Emirates', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'SAR', 'country' => 'Saudi Arabia', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
