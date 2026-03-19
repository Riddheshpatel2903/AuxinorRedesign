<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Acrylates / Monomers', 'icon' => '⚗️', 'sort_order' => 1],
            ['name' => 'Aromatics / Hydrocarbons', 'icon' => '🛢️', 'sort_order' => 2],
            ['name' => 'Glycols / Glycol Ethers', 'icon' => '💧', 'sort_order' => 3],
            ['name' => 'Oxo Alcohols', 'icon' => '🧪', 'sort_order' => 4],
            ['name' => 'Other Products', 'icon' => '📦', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            ProductCategory::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
                'sort_order' => $cat['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
