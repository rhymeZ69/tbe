<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Saudi Arabia',          'code' => 'SA', 'code3' => 'SAU', 'flag_emoji' => '🇸🇦', 'short_label' => 'KSA', 'is_gcc' => true,  'is_featured' => true,  'sort_order' => 1],
            ['name' => 'United Arab Emirates',  'code' => 'AE', 'code3' => 'ARE', 'flag_emoji' => '🇦🇪', 'short_label' => 'UAE', 'is_gcc' => true,  'is_featured' => true,  'sort_order' => 2],
            ['name' => 'Kuwait',                'code' => 'KW', 'code3' => 'KWT', 'flag_emoji' => '🇰🇼', 'short_label' => 'KWT', 'is_gcc' => true,  'is_featured' => true,  'sort_order' => 3],
            ['name' => 'Qatar',                 'code' => 'QA', 'code3' => 'QAT', 'flag_emoji' => '🇶🇦', 'short_label' => 'QAT', 'is_gcc' => true,  'is_featured' => true,  'sort_order' => 4],
            ['name' => 'Bahrain',               'code' => 'BH', 'code3' => 'BHR', 'flag_emoji' => '🇧🇭', 'short_label' => 'BHR', 'is_gcc' => true,  'is_featured' => true,  'sort_order' => 5],
            ['name' => 'Oman',                  'code' => 'OM', 'code3' => 'OMN', 'flag_emoji' => '🇴🇲', 'short_label' => 'OMN', 'is_gcc' => true,  'is_featured' => true,  'sort_order' => 6],
            ['name' => 'Pakistan',              'code' => 'PK', 'code3' => 'PAK', 'flag_emoji' => '🇵🇰', 'short_label' => 'PAK', 'is_gcc' => false, 'is_featured' => false, 'sort_order' => 7],
            ['name' => 'Malaysia',              'code' => 'MY', 'code3' => 'MYS', 'flag_emoji' => '🇲🇾', 'short_label' => 'MYS', 'is_gcc' => false, 'is_featured' => false, 'sort_order' => 8],
            ['name' => 'United Kingdom',        'code' => 'GB', 'code3' => 'GBR', 'flag_emoji' => '🇬🇧', 'short_label' => 'UK',  'is_gcc' => false, 'is_featured' => false, 'sort_order' => 9],
        ];

        foreach ($countries as $row) {
            Country::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}