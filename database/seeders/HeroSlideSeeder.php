<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            ['image_url' => 'https://images.unsplash.com/photo-1565688527997-01c3bb6dfe17?w=1600&q=85', 'sort_order' => 1, 'is_active' => true, 'overlay_opacity' => 0.55],
            ['image_url' => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=1600&q=85', 'sort_order' => 2, 'is_active' => true, 'overlay_opacity' => 0.50],
            ['image_url' => 'https://images.unsplash.com/photo-1581093806997-124204d9fa9d?w=1600&q=85', 'sort_order' => 3, 'is_active' => true, 'overlay_opacity' => 0.48],
            ['image_url' => 'https://images.unsplash.com/photo-1628863354691-58d2c1db8119?w=1600&q=85', 'sort_order' => 4, 'is_active' => true, 'overlay_opacity' => 0.52],
        ];

        foreach ($slides as $slide) {
            HeroSlide::updateOrCreate(['image_url' => $slide['image_url']], $slide);
        }
    }
}
