<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industries = config('industries');

        foreach ($industries as $index => $data) {
            \App\Models\Industry::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name'       => $data['name'],
                    'icon'       => $data['icon'],
                    'desc'       => $data['desc'],
                    'is_active'  => true,
                    'sort_order' => $index * 10,
                ]
            );
        }
    }
}
