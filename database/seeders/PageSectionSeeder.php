<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageSection;

class PageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $homeSections = [
            'hero' => 'Hero Section',
            'services_strip' => 'Services Strip',
            'industries' => 'Industries',
            'products' => 'Products',
            'about_infra' => 'About & Infrastructure',
            'insights' => 'Market Insights',
            'contact' => 'Contact & Enquiry'
        ];

        foreach ($homeSections as $key => $label) {
            PageSection::firstOrCreate(
                ['page_slug' => 'home', 'section_key' => $key],
                ['section_label' => $label, 'sort_order' => 1]
            );
        }

        $aboutSections = [
            'about_hero' => 'About Hero',
            'about_mission' => 'Mission & Story',
            'about_values' => 'Core Values',
            'about_advantage' => 'The Advantage',
            'about_stats' => 'Trust Stats'
        ];

        foreach ($aboutSections as $key => $label) {
            PageSection::firstOrCreate(
                ['page_slug' => 'about', 'section_key' => $key],
                ['section_label' => $label, 'sort_order' => 1]
            );
        }
    }
}
