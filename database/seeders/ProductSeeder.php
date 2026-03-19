<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            'acrylates-monomers' => [
                ['name' => 'Butyl Acrylate', 'formula' => 'C₇H₁₂O₂', 'cas' => '141-32-2'],
                ['name' => '2-Ethylhexyl Acrylate', 'formula' => 'C₁₁H₂₀O₂', 'cas' => '103-11-7'],
                ['name' => 'Ethyl Acrylate', 'formula' => 'C₅H₈O₂', 'cas' => '140-88-5'],
                ['name' => 'Glacial Acrylic Acid', 'formula' => 'C₃H₄O₂', 'cas' => '79-10-7'],
                ['name' => 'Methyl Acrylate', 'formula' => 'C₄H₆O₂', 'cas' => '96-33-3'],
                ['name' => 'Methacrylic Acid', 'formula' => 'C₄H₆O₂', 'cas' => '79-41-4'],
                ['name' => 'Styrene Monomer', 'formula' => 'C₈H₈', 'cas' => '100-42-5'],
                ['name' => 'Vinyl Acetate Monomer', 'formula' => 'C₄H₆O₂', 'cas' => '108-05-4'],
            ],
            'aromatics-hydrocarbons' => [
                ['name' => 'Toluene', 'formula' => 'C₇H₈', 'cas' => '108-88-3'],
                ['name' => 'Xylene (Mixed)', 'formula' => 'C₈H₁₀', 'cas' => '1330-20-7'],
                ['name' => 'Benzene', 'formula' => 'C₆H₆', 'cas' => '71-43-2'],
                ['name' => 'Cyclohexane', 'formula' => 'C₆H₁₂', 'cas' => '110-82-7'],
                ['name' => 'Mixed Xylene', 'formula' => 'C₈H₁₀', 'cas' => '1330-20-7'],
            ],
            'glycols-glycol-ethers' => [
                ['name' => 'Mono Ethylene Glycol', 'formula' => 'C₂H₆O₂', 'cas' => '107-21-1'],
                ['name' => 'Diethylene Glycol', 'formula' => 'C₄H₁₀O₃', 'cas' => '111-46-6'],
                ['name' => 'Propylene Glycol', 'formula' => 'C₃H₈O₂', 'cas' => '57-55-6'],
                ['name' => 'Ethylene Glycol Monobutyl Ether', 'formula' => 'C₆H₁₄O₂', 'cas' => '111-76-2'],
                ['name' => 'Triethylene Glycol', 'formula' => 'C₆H₁₄O₄', 'cas' => '112-27-6'],
            ],
            'oxo-alcohols' => [
                ['name' => 'Iso Butanol', 'formula' => 'C₄H₁₀O', 'cas' => '78-83-1'],
                ['name' => 'Isopropyl Alcohol IPA', 'formula' => 'C₃H₈O', 'cas' => '67-63-0'],
                ['name' => 'Methanol', 'formula' => 'CH₄O', 'cas' => '67-56-1'],
                ['name' => 'n-Butanol', 'formula' => 'C₄H₁₀O', 'cas' => '71-36-3'],
                ['name' => '2-Ethyl Hexanol', 'formula' => 'C₈H₁₈O', 'cas' => '104-76-7'],
            ],
            'other-products' => [
                ['name' => 'Acetone', 'formula' => 'C₃H₆O', 'cas' => '67-64-1'],
                ['name' => 'Ethyl Acetate', 'formula' => 'C₄H₈O₂', 'cas' => '141-78-6'],
                ['name' => 'DMF (Dimethylformamide)', 'formula' => 'C₃H₇NO', 'cas' => '68-12-2'],
            ]
        ];

        foreach ($products as $catSlug => $items) {
            $category = ProductCategory::where('slug', $catSlug)->first();
            if (!$category) continue;

            foreach ($items as $idx => $item) {
                Product::create([
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']),
                    'category_id' => $category->id,
                    'chemical_formula' => $item['formula'],
                    'cas_number' => $item['cas'],
                    'short_description' => 'Industrial grade ' . $item['name'] . ' for manufacturing and bulk processing.',
                    'description' => 'High quality ' . $item['name'] . ' source. Supplied in bulk containers, tankers, or drums as required.',
                    'is_featured' => ($idx === 0), // feature first item
                    'sort_order' => $idx,
                ]);
            }
        }
    }
}
