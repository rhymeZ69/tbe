<?php

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Halal Certified', 'description' => 'Shariah-compliant slaughter and processing.', 'sort_order' => 1],
            ['name' => 'HACCP',           'description' => 'Hazard Analysis & Critical Control Points compliance.', 'sort_order' => 2],
            ['name' => 'ISO 22000',       'description' => 'Food safety management system.', 'sort_order' => 3],
            ['name' => 'Veterinary Health Certificate', 'description' => 'Issued per consignment.', 'sort_order' => 4],
        ];

        foreach ($items as $row) {
            Certification::updateOrCreate(['name' => $row['name']], $row + ['is_active' => true]);
        }
    }
}