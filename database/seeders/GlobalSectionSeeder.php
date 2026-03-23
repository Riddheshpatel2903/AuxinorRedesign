<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class GlobalSectionSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a 'global' page exists for tracking global sections
        $page = Page::updateOrCreate(
            ['slug' => 'global'],
            ['title' => 'Global Elements', 'is_active' => true]
        );

        $sections = [
            ['key' => 'navbar', 'label' => 'Global Navbar'],
            ['key' => 'footer', 'label' => 'Global Footer'],
        ];

        foreach ($sections as $i => $s) {
            PageSection::updateOrCreate(
                ['page_slug' => 'global', 'section_key' => $s['key']],
                [
                    'page_id' => $page->id,
                    'section_label' => $s['label'],
                    'sort_order' => $i,
                    'is_visible' => true
                ]
            );
        }
    }
}
