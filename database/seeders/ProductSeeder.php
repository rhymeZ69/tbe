<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSpec;
use App\Models\ProductVariety;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'meat' => [
                'name' => 'Meat — Beef & Mutton',
                'tagline' => 'Fresh & Chilled',
                'short_description' => 'Tender, well-marbled beef and lean, flavourful mutton from healthy livestock.',
                'varieties' => ['Whole Carcass','Boneless Cube','Topside','Striploin','Tenderloin','Leg','Shoulder','Rack','Chops','Mince'],
                'specs' => [
                    ['Chilled Temperature', '0°C – 4°C'],
                    ['Packaging Options',   'Vacuum / Bulk'],
                    ['Halal Certified',     'Shariah Compliant'],
                    ['Freight Mode',        'Air / Sea'],
                ],
            ],
            'garments' => [
                'name' => 'Ready Made Garments',
                'tagline' => 'Ready Made',
                'short_description' => 'Quality apparel manufactured in Pakistan — from everyday essentials to fashion lines.',
                'varieties' => ['T-Shirts','Polo Shirts','Denim Jeans','Trousers','Knitwear','Hoodies','Shirts','Kids Wear','Uniforms','Custom Labels'],
                'specs' => [
                    ['Service Model',      'OEM / ODM'],
                    ['Size Range',         'Full Size Range'],
                    ['Quality Control',    'Pre-Shipment Inspection'],
                    ['Order Volume',       'Container Loads'],
                ],
            ],
            'rice' => [
                'name' => 'Premium Rice',
                'tagline' => 'Premium Grade',
                'short_description' => "Pakistan's finest rice — long-grain Basmati and quality non-Basmati varieties.",
                'varieties' => ['Super Basmati','1121 Basmati','Long Grain','IRRI-6','IRRI-9','Brown Rice','Parboiled','Broken Rice'],
                'specs' => [
                    ['Bag Sizes',       '5 – 50 kg'],
                    ['Packing Options', 'Custom / Private Label'],
                    ['Milling',         'Sortex Clean'],
                    ['Formats',         'Bulk / Retail'],
                ],
            ],
            'vegetables' => [
                'name' => 'Fresh Vegetables',
                'tagline' => 'Farm Fresh',
                'short_description' => "Farm-fresh produce from Pakistan's fertile regions with full traceability.",
                'varieties' => ['Onion','Potato','Garlic','Ginger','Green Chilli','Lemon','Mango','Kinnow','Seasonal Produce'],
                'specs' => [
                    ['Source',        'Farm Sourced'],
                    ['Packing',       'Mesh / Carton'],
                    ['Grading',       'Size & Quality Sorted'],
                    ['Freight Mode',  'Air / Sea (Reefer)'],
                ],
            ],
        ];

        foreach ($data as $slug => $row) {
            $category = ProductCategory::where('slug', $slug)->first();
            if (!$category) continue;

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id'       => $category->id,
                    'name'              => $row['name'],
                    'tagline'           => $row['tagline'],
                    'short_description' => $row['short_description'],
                    'is_active'         => true,
                    'is_featured'       => true,
                ]
            );

            foreach ($row['varieties'] as $i => $varietyName) {
                ProductVariety::updateOrCreate(
                    ['product_id' => $product->id, 'name' => $varietyName],
                    ['sort_order' => $i]
                );
            }

            foreach ($row['specs'] as $i => $spec) {
                ProductSpec::updateOrCreate(
                    ['product_id' => $product->id, 'label' => $spec[0]],
                    ['value' => $spec[1], 'sort_order' => $i]
                );
            }
        }
    }
}