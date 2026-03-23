<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'meta_title' => 'Auxinor Chemicals - Chemical Trading & Distribution',
                'meta_description' => 'Specialists in procurement, bulk distribution, and surplus chemical trading since 2017.',
            ],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'meta_title' => 'About Auxinor Chemicals | Our Mission & Values',
                'meta_description' => 'Learn about our commitment to excellence and integrity in industrial chemical distribution.',
            ],
            [
                'title' => 'Products',
                'slug' => 'products',
                'meta_title' => 'Chemical Product Catalogue | Auxinor Chemicals',
                'meta_description' => 'Browse our complete range of industrial chemicals, solvents, and specialized formulations.',
            ],
            [
                'title' => 'Industries',
                'slug' => 'industries',
                'meta_title' => 'Industries We Serve | Auxinor Chemicals',
                'meta_description' => 'Supplying high-purity materials for Pharmaceuticals, Agrochemicals, and Speciality Chemicals.',
            ],
            [
                'title' => 'Infrastructure',
                'slug' => 'infrastructure',
                'meta_title' => 'Our Infrastructure & Logistics | Auxinor Chemicals',
                'meta_description' => 'Explore our warehousing facilities and robust pan-India supply chain excellence.',
            ],
            [
                'title' => 'Insights',
                'slug' => 'insights',
                'meta_title' => 'Chemical Market Insights | Auxinor Chemicals',
                'meta_description' => 'Trade recommendations and price intelligence from our chemical experts.',
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'meta_title' => 'Contact Auxinor Chemicals | Get a Quote',
                'meta_description' => 'Reach out to our sales team for material specifications, bulk pricing, or logistics queries.',
            ],
        ];

        foreach ($pages as $page) {
            \App\Models\Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
