<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Industry::truncate();

        $industries = [
            [
                'name' => 'Pharmaceuticals',
                'icon' => '🧪',
                'description' => 'Providing IPA, extreme purity solvents, and intermediates used widely across Active Pharmaceutical Ingredient (API) production lines.',
                'products' => 'Isopropyl Alcohol (IPA), Acetone, Toluene',
                'image_path' => '/assets/images/industry-1.jpg',
                'order' => 1
            ],
            [
                'name' => 'Agrochemicals',
                'icon' => '🌱',
                'description' => 'Specialized intermediate compounds acting as carriers and active solubilizers in pesticide and herbicide formulations.',
                'products' => 'Toluene, Selected Glycols',
                'image_path' => '/assets/images/industry-2.jpg',
                'order' => 2
            ],
            [
                'name' => 'Paints & Coatings',
                'icon' => '🎨',
                'description' => 'Resins, pigments, and thinning agents for industrial finishes.',
                'products' => 'Resins, Pigments, Solvents',
                'image_path' => '/assets/images/industry-3.jpg',
                'order' => 3
            ],
            [
                'name' => 'Speciality Chemicals',
                'icon' => '🧪',
                'description' => 'Supplying high-purity monomers, acrylates, and solvents essential for the manufacturing of specialty chemical grades, coatings, and adhesives.',
                'products' => 'Acrylates, Toluene, Benzene, Acetone',
                'image_path' => '/assets/images/industry-4.jpg',
                'order' => 4
            ],
            [
                'name' => 'Petrochemicals',
                'icon' => '🏭',
                'description' => 'As core building blocks, we distribute bulk shipments of foundational petrochemicals serving robust industrial applications.',
                'products' => 'Mixed Xylene, Base Monomers, Glycols',
                'image_path' => '/assets/images/industry-5.jpg',
                'order' => 5
            ],
            [
                'name' => 'Food Industry',
                'icon' => '🍎',
                'description' => 'Safe, highly regulated food-grade packaging chemicals and compliant additives used throughout food preservation.',
                'products' => 'Propylene Glycol, Selected Oxo Alcohols',
                'image_path' => '/assets/images/industry-6.jpg',
                'order' => 6
            ],
        ];

        foreach ($industries as $data) {
            Industry::create($data);
        }
    }
}
