<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Meat', 'slug' => 'meat', 'tagline' => 'Fresh & Chilled',
                'short_description' => 'Premium halal beef and mutton from healthy livestock, processed under certified standards.',
                'gradient_class' => 'prod-top',
                'sort_order' => 1, 'is_featured' => true,
            ],
            [
                'name' => 'Ready Made Garments', 'slug' => 'garments', 'tagline' => 'Ready Made',
                'short_description' => 'Quality apparel manufactured in Pakistan — OEM, ODM and private label ready.',
                'gradient_class' => 'prod-top garments',
                'sort_order' => 2, 'is_featured' => true,
            ],
            [
                'name' => 'Rice', 'slug' => 'rice', 'tagline' => 'Premium Grade',
                'short_description' => "Pakistan's finest Basmati and non-Basmati rice varieties, milled and graded for export.",
                'gradient_class' => 'prod-top rice',
                'sort_order' => 3, 'is_featured' => true,
            ],
            [
                'name' => 'Vegetables', 'slug' => 'vegetables', 'tagline' => 'Farm Fresh',
                'short_description' => "Farm-fresh produce from Pakistan's fertile regions, harvested and packed for export.",
                'gradient_class' => 'prod-top vegetables',
                'sort_order' => 4, 'is_featured' => true,
            ],
        ];

        foreach ($categories as $row) {
            ProductCategory::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }
}