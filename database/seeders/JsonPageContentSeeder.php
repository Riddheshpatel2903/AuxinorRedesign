<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class JsonPageContentSeeder extends Seeder
{
    public function run(): void
    {
        $content = [
            'home' => [
                [
                    'type' => 'hero',
                    'props' => [
                        'label' => 'Chemical Trading & Distribution',
                        'heading' => 'Trusted Chemical Partners',
                        'subheading' => "Specialists in procurement, bulk distribution, and surplus chemical trading — serving India's top industrial sectors since 2017.",
                        'button_text' => 'Explore Products',
                        'button_link' => '/products',
                    ]
                ],
                [
                    'type' => 'services-strip',
                    'props' => [
                        'title' => 'Our Core Services',
                        'items' => [
                            ['title' => 'Chemical Trading'],
                            ['title' => 'Distribution & Logistics'],
                            ['title' => 'Bulk Warehousing'],
                            ['title' => 'Market Intelligence'],
                        ]
                    ]
                ],
                [
                    'type' => 'home-about',
                    'props' => [
                        'title' => 'Committed to Excellence',
                        'subtitle' => 'With over 40 years of composite industry experience, Auxinor Chemicals LLP is a leading distributor of industrial chemicals based in Ahmedabad.',
                        'button_text' => 'Our Infrastructure',
                    ]
                ],
                [
                    'type' => 'catalogue-grid',
                    'props' => [
                        'title' => 'Featured Products',
                        'catalogue_type' => 'products',
                    ]
                ]
            ],
            'about' => [
                [
                    'type' => 'hero',
                    'props' => [
                        'label' => 'About Auxinor Chemicals',
                        'heading' => 'Trading With Integrity',
                        'subheading' => 'Building trust through quality and reliability in the chemical industry.',
                    ]
                ],
                [
                    'type' => 'mission',
                    'props' => [
                        'title' => 'Our Mission',
                        'description' => 'To be the most trusted partner in the chemical industry by providing sustainable solutions, unparalleled expertise, and absolute transparency in every trade.',
                    ]
                ],
                [
                    'type' => 'features',
                    'props' => [
                        'title' => 'Our Core Values',
                        'items' => [
                            ['title' => 'Safety First', 'description' => 'Highest international standards for handling.'],
                            ['title' => 'Transparency', 'description' => 'Absolute clarity in pricing and specs.'],
                            ['title' => 'Reliability', 'description' => 'Ensuring consistent availability.'],
                        ]
                    ]
                ]
            ],
            'industries' => [
                [
                    'type' => 'hero',
                    'props' => [
                        'label' => 'Market Verticals',
                        'heading' => 'Powering Diverse Industrial Sectors',
                        'subheading' => 'Supplying high-purity materials for Pharmaceuticals, Agrochemicals, and Speciality Chemicals.',
                    ]
                ],
                [
                    'type' => 'grid',
                    'props' => [
                        'title' => 'Industries We Serve',
                        'items' => [
                            ['title' => 'Speciality Chemicals', 'description' => 'Supplying high-purity monomers and solvents.'],
                            ['title' => 'Agrochemicals', 'description' => 'Raw materials for crop protection.'],
                            ['title' => 'Pharmaceuticals', 'description' => 'High-purity solvents for API manufacturing.'],
                        ]
                    ]
                ]
            ],
            'infrastructure' => [
                [
                    'type' => 'hero',
                    'props' => [
                        'label' => 'Our Operations',
                        'heading' => 'Infrastructure & Logistics',
                        'subheading' => 'Explore our warehousing facilities and robust pan-India supply chain.',
                    ]
                ],
                [
                    'type' => 'content',
                    'props' => [
                        'label' => '01. Storage Facilities',
                        'title' => 'Bulk Storage & Warehousing',
                        'description' => 'State-of-the-art warehousing facilities in Ahmedabad.',
                        'image_position' => 'right',
                    ]
                ],
                [
                    'type' => 'content',
                    'props' => [
                        'label' => '02. Supply Chain',
                        'title' => 'Pan-India Logistics',
                        'description' => 'Robust logistical network ensuring secure and timely dispatch.',
                        'image_position' => 'left',
                    ]
                ]
            ],
            'products' => [
                [
                    'type' => 'hero',
                    'props' => [
                        'label' => 'Catalogue',
                        'heading' => 'Product Catalogue',
                        'subheading' => 'Browse our complete range of industrial chemicals.',
                    ]
                ],
                [
                    'type' => 'catalogue-grid',
                    'props' => [
                        'title' => 'Our Chemical Portfolio',
                        'catalogue_type' => 'products',
                    ]
                ]
            ],
            'insights' => [
                [
                    'type' => 'hero',
                    'props' => [
                        'label' => 'Market Intelligence',
                        'heading' => 'Market Insights',
                        'subheading' => 'Trade recommendations and price intelligence from our chemical experts.',
                    ]
                ],
                [
                    'type' => 'catalogue-grid',
                    'props' => [
                        'title' => 'Latest Insights',
                        'catalogue_type' => 'insights',
                    ]
                ]
            ],
            'contact' => [
                [
                    'type' => 'hero',
                    'props' => [
                        'label' => 'Get In Touch',
                        'heading' => 'Initiate a Partnership',
                    ]
                ],
                [
                    'type' => 'contact',
                    'props' => [
                        'title' => 'Send an Enquiry',
                        'description' => 'Have questions? We are here to help.',
                        'submit_button_text' => 'Dispatch Message',
                    ]
                ]
            ],
        ];

        foreach ($content as $slug => $sections) {
            Page::where('slug', $slug)->update(['content' => $sections]);
        }
    }
}
