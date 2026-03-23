<?php

namespace Database\Seeders;

use App\Models\PageSection;
use App\Models\PageElement;
use Illuminate\Database\Seeder;

class PageElementSeeder extends Seeder
{
    public function run(): void
    {
        $elements = [
            'home' => [
                'hero' => [
                    ['key' => 'hero_label', 'type' => 'text', 'content' => 'Chemical Trading & Distribution'],
                    ['key' => 'el_setting:hero_headline_line1', 'type' => 'text', 'content' => 'Trusted'],
                    ['key' => 'el_setting:hero_headline_line2', 'type' => 'text', 'content' => 'Chemical'],
                    ['key' => 'el_setting:hero_headline_line3', 'type' => 'text', 'content' => 'Partners'],
                    ['key' => 'el_setting:hero_subtext', 'type' => 'text', 'content' => "Specialists in procurement, bulk distribution, and surplus chemical trading — serving India's top industrial sectors since 2017."],
                    ['key' => 'el_setting:hero_cta_primary', 'type' => 'text', 'content' => 'Explore Products'],
                    ['key' => 'el_setting:hero_cta_secondary', 'type' => 'text', 'content' => 'Get a Quote'],
                    ['key' => 'stat_years_count', 'type' => 'text', 'content' => '7'],
                    ['key' => 'stat_products_count', 'type' => 'text', 'content' => '80'],
                    ['key' => 'stat_industries_count', 'type' => 'text', 'content' => '6'],
                    ['key' => 'stat_reach_val', 'type' => 'text', 'content' => 'Pan-India'],
                ],
                'services_strip' => [
                    ['key' => 'service_1_title', 'type' => 'text', 'content' => 'Chemical <em class="font-serif italic text-teal-2">Trading</em>'],
                    ['key' => 'service_1_desc',  'type' => 'text', 'content' => 'Premium bulk solvents and monomers sourced from global tier-1 manufacturers.'],
                    ['key' => 'service_1_label', 'type' => 'text', 'content' => 'Learn More'],
                    ['key' => 'service_2_title', 'type' => 'text', 'content' => 'Distribution & <em class="font-serif italic text-teal-2">Logistics</em>'],
                    ['key' => 'service_2_desc',  'type' => 'text', 'content' => 'Tailored for manufacturing needs.'],
                    ['key' => 'service_2_label', 'type' => 'text', 'content' => 'Learn More'],
                    ['key' => 'service_3_title', 'type' => 'text', 'content' => 'Bulk <em class="font-serif italic text-teal-2">Warehousing</em>'],
                    ['key' => 'service_3_desc',  'type' => 'text', 'content' => 'Pan-India supply chain excellence.'],
                    ['key' => 'service_3_label', 'type' => 'text', 'content' => 'Learn More'],
                    ['key' => 'service_4_title', 'type' => 'text', 'content' => 'Market <em class="font-serif italic text-teal-2">Intelligence</em>'],
                    ['key' => 'service_4_desc',  'type' => 'text', 'content' => 'Expert trade recommendations.'],
                    ['key' => 'service_4_label', 'type' => 'text', 'content' => 'Learn More'],
                ],
                'home_about' => [
                    ['key' => 'home_about_title', 'type' => 'text', 'content' => 'Committed to <em class="font-serif italic text-teal font-normal">Excellence</em>'],
                    ['key' => 'home_about_subtitle', 'type' => 'text', 'content' => 'With over 40 years of composite industry experience, Auxinor Chemicals LLP is a leading distributor of industrial chemicals based in Ahmedabad.'],
                    ['key' => 'home_about_button_text', 'type' => 'text', 'content' => 'Our Infrastructure'],
                ],
                'home_industries' => [
                    ['key' => 'home_industries_title', 'type' => 'text', 'content' => 'Industries <em class="font-serif italic text-teal font-normal">We Serve</em>'],
                    ['key' => 'home_industries_subtitle', 'type' => 'text', 'content' => 'From pharmaceuticals to agrochemicals, we provide high-purity materials essential for diverse manufacturing sectors.'],
                ],
                'home_products' => [
                    ['key' => 'home_products_title', 'type' => 'text', 'content' => 'Featured <em class="font-serif italic text-teal font-normal">Products</em>'],
                    ['key' => 'home_products_subtitle', 'type' => 'text', 'content' => 'Explore our high-demand chemical range sourced from top global manufacturers.'],
                ],
                'about_infra' => [
                    ['key' => 'about_label', 'type' => 'text', 'content' => 'Our Legacy'],
                    ['key' => 'el_setting:about_title', 'type' => 'text', 'content' => 'Seven Years of <em class="font-serif italic text-teal-2 font-normal">Chemical</em> Excellence'],
                    ['key' => 'el_setting:about_short', 'type' => 'text', 'content' => 'Auxinor Chemicals LLP is a B2B chemical trading company based in Ahmedabad, Gujarat.'],
                    ['key' => 'el_setting:about_long', 'type' => 'text', 'content' => 'Our strength lies in maintaining a strong vendor and sourcing network, providing competitive pricing, and ensuring reliable supply chains.'],
                    ['key' => 'infra_card_1', 'type' => 'text', 'content' => 'Warehousing Facilities'],
                    ['key' => 'infra_card_2', 'type' => 'text', 'content' => 'Pan-India Logistics'],
                    ['key' => 'infra_card_3', 'type' => 'text', 'content' => 'Vendor Network'],
                    ['key' => 'infra_card_4', 'type' => 'text', 'content' => 'Inventory Mgmt'],
                ],
                'home_insights' => [
                    ['key' => 'home_insights_title', 'type' => 'text', 'content' => 'Market <em class="font-serif italic text-teal font-normal">Insights</em>'],
                    ['key' => 'home_insights_subtitle', 'type' => 'text', 'content' => 'The latest trade recommendations and price intelligence from our chemical experts.'],
                ],
                'contact' => [
                    ['key' => 'el_setting:office_address', 'type' => 'text', 'content' => 'Ahmedabad, Gujarat, India'],
                    ['key' => 'el_setting:phone_primary', 'type' => 'text', 'content' => '+91 99099 07231'],
                    ['key' => 'el_setting:email_sales', 'type' => 'text', 'content' => 'sales@auxinorchem.com'],
                ],
            ],
            'about' => [
                'about_hero' => [
                    ['key' => 'about_hero_label', 'type' => 'text', 'content' => 'About Auxinor Chemicals'],
                    ['key' => 'about_hero_title', 'type' => 'text', 'content' => 'Trading With <em class="font-serif italic font-normal text-teal-2">Integrity</em>'],
                ],
                'about_mission' => [
                    ['key' => 'about_mission_title', 'type' => 'text', 'content' => 'Our mission is to catalyze global commerce by embodying values and fostering long-term relationships.'],
                    ['key' => 'about_mission_story', 'type' => 'text', 'content' => "Grounded in entrepreneurial spirit, we ensure sustained growth through honest, accountable chemical trading. We specialize in bulk procurement and supply chain optimization."],
                ],
                'about_values' => [
                    ['key' => 'about_values_label', 'type' => 'text', 'content' => 'Core Principles'],
                    ['key' => 'about_values_title', 'type' => 'text', 'content' => 'Our <em class="font-serif italic text-teal font-normal">Values</em>'],
                    ['key' => 'value_card_1', 'type' => 'text', 'content' => 'Teamwork & Trust'],
                    ['key' => 'value_card_1_desc', 'type' => 'text', 'content' => 'We foster collaborative partnerships with both vendors and clients.'],
                    ['key' => 'value_card_2', 'type' => 'text', 'content' => 'Ownership'],
                    ['key' => 'value_card_2_desc', 'type' => 'text', 'content' => 'We take full accountability for our chemical supply chain.'],
                    ['key' => 'value_card_3', 'type' => 'text', 'content' => 'Sustainability'],
                    ['key' => 'value_card_3_desc', 'type' => 'text', 'content' => 'Committed to ethical trading practices.'],
                ],
                'about_advantage' => [
                    ['key' => 'about_advantage_label', 'type' => 'text', 'content' => 'The Auxinor Advantage'],
                    ['key' => 'about_advantage_title', 'type' => 'text', 'content' => 'Why Partner With <em class="font-serif italic text-teal font-normal">Us?</em>'],
                    ['key' => 'about_advantage_image', 'type' => 'image', 'content' => ''],
                    ['key' => 'advantage_1_title', 'type' => 'text', 'content' => 'Quality Assurance'],
                    ['key' => 'advantage_1_desc', 'type' => 'text', 'content' => 'Stringent quality checks and documentation for all chemical consignments.'],
                    ['key' => 'advantage_2_title', 'type' => 'text', 'content' => 'Reliable Vendor Network'],
                    ['key' => 'advantage_2_desc', 'type' => 'text', 'content' => 'Direct relationships with top-tier manufacturers ensuring competitive pricing.'],
                    ['key' => 'advantage_3_title', 'type' => 'text', 'content' => 'Surplus Optimization'],
                    ['key' => 'advantage_3_desc', 'type' => 'text', 'content' => 'Specialized in moving surplus chemicals efficiently to minimize industrial waste.'],
                ],
                'about_stats' => [
                    ['key' => 'about_stat_years', 'type' => 'text', 'content' => '7'],
                    ['key' => 'about_stat_products', 'type' => 'text', 'content' => '80'],
                    ['key' => 'about_stat_industries', 'type' => 'text', 'content' => '6'],
                    ['key' => 'about_stat_deliveries', 'type' => 'text', 'content' => '100'],
                ],
            ],
            'contact' => [
                'contact_hero' => [
                    ['key' => 'contact_hero_title', 'type' => 'text', 'content' => 'Contact <em class="font-serif italic text-teal-2 font-normal">Us</em>'],
                ],
                'contact_form' => [
                    ['key' => 'contact_form_title', 'type' => 'text', 'content' => "Let's Discuss Your <em class='font-serif italic text-teal font-normal'>Requirements</em>"],
                    ['key' => 'contact_form_subtitle', 'type' => 'text', 'content' => 'Reach out to our sales and technical team for material specifications, bulk pricing, or logistics queries.'],
                    ['key' => 'contact_office_address', 'type' => 'text', 'content' => 'Ahmedabad, Gujarat, India'],
                    ['key' => 'contact_warehouse_address', 'type' => 'text', 'content' => 'Aslali, Ahmedabad, Gujarat'],
                    ['key' => 'contact_phone_primary', 'type' => 'text', 'content' => '+91 99099 07231'],
                    ['key' => 'contact_phone_secondary', 'type' => 'text', 'content' => ''],
                    ['key' => 'contact_email_sales', 'type' => 'text', 'content' => 'sales@auxinorchem.com'],
                    ['key' => 'contact_email_info', 'type' => 'text', 'content' => 'info@auxinorchem.com'],
                ],
            ],
            'industries' => [
                'industries_hero' => [
                    ['key' => 'industries_hero_label', 'type' => 'text', 'content' => 'Our Reach'],
                    ['key' => 'industries_hero_title', 'type' => 'text', 'content' => 'Industries We <em class="font-serif italic font-normal text-teal-2">Serve</em>'],
                ],
                'industries_grid' => [
                    ['key' => 'ind_1_name', 'type' => 'text', 'content' => 'Speciality Chemicals'],
                    ['key' => 'ind_1_desc', 'type' => 'text', 'content' => 'Supplying high-purity monomers, acrylates, and solvents.'],
                    ['key' => 'ind_1_products', 'type' => 'text', 'content' => 'Acrylates, Toluene, Benzene, Acetone'],
                    ['key' => 'ind_2_name', 'type' => 'text', 'content' => 'Agrochemicals'],
                    ['key' => 'ind_2_desc', 'type' => 'text', 'content' => 'Providing essential raw materials for crop protection and fertilizers.'],
                    ['key' => 'ind_2_products', 'type' => 'text', 'content' => 'Solvents, Surfactants, Intermediates'],
                    ['key' => 'ind_3_name', 'type' => 'text', 'content' => 'Pharmaceuticals'],
                    ['key' => 'ind_3_desc', 'type' => 'text', 'content' => 'High-purity solvents for API manufacturing and synthesis.'],
                    ['key' => 'ind_3_products', 'type' => 'text', 'content' => 'MDC, Methanol, IPA, THF'],
                ],
                'industries_cta' => [
                    ['key' => 'industries_cta_title', 'type' => 'text', 'content' => "Don't see your industry listed?"],
                    ['key' => 'industries_cta_subtitle', 'type' => 'text', 'content' => 'We supply to various niche manufacturing sectors. Contact us for custom chemical procurement strategies.'],
                ],
            ],
            'infrastructure' => [
                'infra_hero' => [
                    ['key' => 'infra_hero_label', 'type' => 'text', 'content' => 'Our Operations'],
                    ['key' => 'infra_hero_title', 'type' => 'text', 'content' => 'Infrastructure & <em class="font-serif italic font-normal text-teal-2">Logistics</em>'],
                ],
                'infra_content' => [
                    ['key' => 'infra_content_label', 'type' => 'text', 'content' => '01. Storage Facilities'],
                    ['key' => 'infra_content_title', 'type' => 'text', 'content' => 'Bulk Storage & <em class="font-serif italic text-teal font-normal">Warehousing</em>'],
                    ['key' => 'infra_content_desc', 'type' => 'text', 'content' => 'Our state-of-the-art warehousing facilities in Ahmedabad are equipped to handle large volumes of liquid and solid industrial chemicals safely and efficiently.'],
                    ['key' => 'infra_feature_1', 'type' => 'text', 'content' => 'Dedicated petroleum & hazmat storage zones'],
                    ['key' => 'infra_feature_2', 'type' => 'text', 'content' => 'Temperature-controlled solvent silos'],
                    ['key' => 'infra_feature_3', 'type' => 'text', 'content' => 'Strict adherence to safety protocols (PESO compliant)'],
                ],
                'infra_logistics' => [
                    ['key' => 'infra_logistics_label', 'type' => 'text', 'content' => '02. Supply Chain'],
                    ['key' => 'infra_logistics_title', 'type' => 'text', 'content' => 'Pan-India <em class="font-serif italic text-teal font-normal">Logistics</em>'],
                    ['key' => 'infra_logistics_desc', 'type' => 'text', 'content' => 'From bulk tankers to ISO container deliveries, our robust logistical network ensures secure and timely dispatch of chemicals across all major industrial hubs in India.'],
                    ['key' => 'infra_stat_1_val', 'type' => 'text', 'content' => 'GPS Monitored'],
                    ['key' => 'infra_stat_1_desc', 'type' => 'text', 'content' => 'Real-time truck tracing'],
                    ['key' => 'infra_stat_2_val', 'type' => 'text', 'content' => 'Custom Packaging'],
                    ['key' => 'infra_stat_2_desc', 'type' => 'text', 'content' => 'Drums, IBCs, Tankers'],
                ],
                'infra_vendor' => [
                    ['key' => 'infra_vendor_label', 'type' => 'text', 'content' => '03. Procurement'],
                    ['key' => 'infra_vendor_title', 'type' => 'text', 'content' => 'Global <em class="font-serif italic text-teal font-normal">Vendor Network</em>'],
                    ['key' => 'infra_vendor_desc', 'type' => 'text', 'content' => 'Our established relationships with major chemical manufacturers in India and abroad allow us to source premium materials.'],
                    ['key' => 'vendor_stat_1_val', 'type' => 'text', 'content' => 'Tier 1'],
                    ['key' => 'vendor_stat_1_desc', 'type' => 'text', 'content' => 'Direct Manufacturer Tie-ups'],
                    ['key' => 'vendor_stat_2_val', 'type' => 'text', 'content' => 'Surplus'],
                    ['key' => 'vendor_stat_2_desc', 'type' => 'text', 'content' => 'Asset Recovery Trading'],
                    ['key' => 'vendor_stat_3_val', 'type' => 'text', 'content' => 'Quality'],
                    ['key' => 'vendor_stat_3_desc', 'type' => 'text', 'content' => 'Pre-dispatch Checks'],
                ],
                'infra_cta' => [
                    ['key' => 'infra_cta_title', 'type' => 'text', 'content' => 'Need reliable bulk chemical supply?'],
                ],
            ],
            'products' => [
                'products_hero' => [
                    ['key' => 'products_hero_title', 'type' => 'text', 'content' => 'Product <em class="font-serif italic font-normal text-teal">Catalogue</em>'],
                    ['key' => 'products_hero_desc', 'type' => 'text', 'content' => 'Browse our complete range of industrial chemicals, encompassing bulk solvents, monomers, and specialized formulations.'],
                ],
            ],
            'insights' => [
                'insights_hero' => [
                    ['key' => 'insights_hero_title', 'type' => 'text', 'content' => 'Market <em class="font-serif italic text-teal-2 font-normal">Insights</em>'],
                    ['key' => 'insights_hero_desc', 'type' => 'text', 'content' => 'Trade recommendations, price intelligence, and internal news from the desk of Auxinor Chemicals.'],
                ],
            ],
        ];


        foreach ($elements as $pageSlug => $sections) {
            foreach ($sections as $sectionKey => $els) {
                $section = PageSection::where('page_slug', $pageSlug)->where('section_key', $sectionKey)->first();
                if (!$section) continue;

                foreach ($els as $i => $el) {
                    PageElement::updateOrCreate(
                        ['section_id' => $section->id, 'element_key' => $el['key']],
                        [
                            'element_type' => $el['type'],
                            'content' => $el['content'],
                            'sort_order' => $i
                        ]
                    );
                }
            }
        }
    }
}
