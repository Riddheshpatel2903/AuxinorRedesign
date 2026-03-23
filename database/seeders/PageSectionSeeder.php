<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionContent;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'home' => [
                [
                    'key' => 'hero',
                    'type' => 'hero',
                    'label' => 'Hero Banner',
                    'content' => [
                        'heading' => 'Innovative Chemical Solutions',
                        'subheading' => 'Global expertise in distribution and logistics for chemical specialties.',
                        'button_text' => 'Explore Products',
                        'button_link' => '/products',
                    ]
                ],
                [
                    'key' => 'services',
                    'type' => 'services_strip',
                    'label' => 'Our Services',
                    'content' => [
                        'service_1_title' => 'Quality Distribution',
                        'service_2_title' => 'Global Logistics',
                        'service_3_title' => 'Technical Support',
                        'service_4_title' => 'Custom Packaging',
                    ]
                ],
                [
                    'key' => 'about_preview',
                    'type' => 'home_about',
                    'label' => 'About Preview',
                    'content' => [
                        'title' => 'Committed to Excellence',
                        'subtitle' => 'We provide end-to-end solutions for the chemical industry.',
                        'button_text' => 'Read More',
                    ]
                ],
                [
                    'key' => 'cta',
                    'type' => 'cta',
                    'label' => 'Connect CTA',
                    'content' => [
                        'title' => 'Ready to grow your business?',
                        'subtitle' => 'Partner with us for reliable chemical supply chains.',
                        'button_text' => 'Contact Us',
                    ]
                ],
            ],
            'about' => [
                [
                    'key' => 'about_hero',
                    'type' => 'hero',
                    'label' => 'About Hero',
                    'content' => [
                        'heading' => 'Our Legacy of Innovation',
                        'subheading' => 'Building trust through quality and reliability since 2016.',
                    ]
                ],
                [
                    'key' => 'mission',
                    'type' => 'mission',
                    'label' => 'Mission Statement',
                    'content' => [
                        'title' => 'Reliable solutions for a sustainable future.',
                        'description' => '<p>We strive to be the most trusted partner in the chemical industry.</p>',
                    ]
                ],
                [
                    'key' => 'stats',
                    'type' => 'stats',
                    'label' => 'Company Stats',
                    'content' => [
                        'title' => 'Our Impact at a Glance',
                        'stat_1_number' => '7+',
                        'stat_1_label' => 'Years Experience',
                        'stat_2_number' => '80+',
                        'stat_2_label' => 'Products',
                        'stat_3_number' => '500+',
                        'stat_3_label' => 'Clients',
                        'stat_4_number' => 'Pan-India',
                        'stat_4_label' => 'Reach',
                    ]
                ],
            ],
            'infrastructure' => [
                [
                    'key' => 'infra_hero',
                    'type' => 'hero',
                    'label' => 'Infrastructure Hero',
                    'content' => [
                        'heading' => 'World-Class Infrastructure',
                        'subheading' => 'State-of-the-art warehousing and logistics facilities.',
                    ]
                ],
                [
                    'key' => 'content_1',
                    'type' => 'content',
                    'label' => 'Warehousing Detail',
                    'content' => [
                        'title' => 'Strategic Warehousing',
                        'description' => '<p>Our climate-controlled warehouses ensure product integrity at every step.</p>',
                        'image_position' => 'right',
                    ]
                ],
                [
                    'key' => 'cta_infra',
                    'type' => 'cta',
                    'label' => 'Infra CTA',
                    'content' => [
                        'title' => 'Visit Our Facilities',
                        'subtitle' => 'Schedule a tour of our modern distribution centers.',
                        'button_text' => 'Schedule Tour',
                    ]
                ],
            ],
            'contact' => [
                [
                    'key' => 'contact_hero',
                    'type' => 'hero',
                    'label' => 'Get in Touch',
                    'content' => [
                        'heading' => 'How can we help you?',
                        'subheading' => 'Our team is ready to assist with your chemical needs.',
                    ]
                ],
                [
                    'key' => 'main_contact',
                    'type' => 'contact',
                    'label' => 'Contact Form',
                    'content' => [
                        'title' => 'Send us a message',
                        'description' => 'We usually respond within 24 hours.',
                        'submit_button_text' => 'Submit Enquiry',
                    ]
                ],
            ]
        ];

        foreach ($pages as $slug => $sections) {
            $page = Page::where('slug', $slug)->first();
            if (!$page) continue;

            foreach ($sections as $index => $s) {
                $pageSection = PageSection::updateOrCreate(
                    ['page_id' => $page->id, 'section_key' => $s['key']],
                    [
                        'page_slug' => $slug,
                        'type' => $s['type'],
                        'section_type' => $s['type'],
                        'section_label' => $s['label'],
                        'sort_order' => $index,
                        'is_visible' => true,
                    ]
                );

                // Populate section contents
                if (isset($s['content'])) {
                    foreach ($s['content'] as $contentKey => $value) {
                        SectionContent::updateOrCreate(
                            ['section_id' => $pageSection->id, 'key' => $contentKey],
                            [
                                'type' => $this->guessType($contentKey, $value),
                                'value' => $value,
                            ]
                        );
                    }
                }
            }
        }
    }

    private function guessType($key, $value): string
    {
        if (str_contains($key, 'image')) return 'image';
        if (str_contains($key, 'description') || str_contains($key, 'subtitle')) return 'html';
        return 'text';
    }
}
