<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'MEG Price Outlook: What Industrial Buyers Should Know in 2024',
                'category' => 'Market Update',
                'excerpt' => 'An analysis of MEG price trends and what buyers should expect in the coming year.',
                'image' => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=600&q=80',
            ],
            [
                'title' => 'How Surplus Chemical Trading Creates Value for Manufacturers',
                'category' => 'Trading Guide',
                'excerpt' => 'Discover the hidden value in surplus chemical trading to optimize your supply chain.',
                'image' => 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?w=400&q=80',
            ],
            [
                'title' => 'Bulk Chemical Logistics in India: Challenges & Solutions',
                'category' => 'Logistics',
                'excerpt' => 'Navigating the complex landscape of bulk chemical logistics and distribution in India.',
                'image' => 'https://images.unsplash.com/photo-1581093806997-124204d9fa9d?w=600&q=80',
            ]
        ];

        foreach ($posts as $post) {
            BlogPost::create([
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'category' => $post['category'],
                'excerpt' => $post['excerpt'],
                'content' => '<p>' . $post['excerpt'] . '</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vehicula aliquet lectus vel consectetur.</p>',
                'featured_image' => $post['image'],
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
