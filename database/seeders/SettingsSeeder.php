<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => ['val' => 'Auxinor Chemicals LLP', 'type' => 'text', 'label' => 'Company Name'],
            'company_tagline' => ['val' => 'Trusted Chemical Partners Since 2017', 'type' => 'text', 'label' => 'Tagline'],
            'company_established' => ['val' => '2017', 'type' => 'text', 'label' => 'Established Year'],
            'hero_subtext' => ['val' => "Specialists in procurement, bulk distribution, and surplus chemical trading — serving India's top industrial sectors since 2017.", 'type' => 'textarea', 'label' => 'Hero Subtext'],
            'stat_years' => ['val' => '7', 'type' => 'number', 'label' => 'Stat: Years of Experience'],
            'stat_products' => ['val' => '80', 'type' => 'number', 'label' => 'Stat: Active Products'],
            'stat_industries' => ['val' => '6', 'type' => 'number', 'label' => 'Stat: Industries Served'],
            'stat_reach' => ['val' => 'Pan-India', 'type' => 'text', 'label' => 'Stat: Market Reach'],
            'office_address' => ['val' => 'Ganesh Glory 11, Jagatpur, Gota, Ahmedabad, Gujarat 382481', 'type' => 'textarea', 'label' => 'Office Address'],
            'warehouse_address' => ['val' => '14 Yogeshwer Estate, Aslali, Ahmedabad, Gujarat', 'type' => 'textarea', 'label' => 'Warehouse Address'],
            'phone_primary' => ['val' => '+91 990-990-7230', 'type' => 'phone', 'label' => 'Primary Phone'],
            'phone_secondary' => ['val' => '+91 990-990-7231', 'type' => 'phone', 'label' => 'Secondary Phone'],
            'email_info' => ['val' => 'info@auxinorchem.com', 'type' => 'email', 'label' => 'General Inquiry Email'],
            'email_sales' => ['val' => 'sales@auxinorchem.com', 'type' => 'email', 'label' => 'Sales Inquiry Email'],
            'whatsapp_number' => ['val' => '9909907231', 'type' => 'text', 'label' => 'WhatsApp Number'],
            'google_maps_embed' => ['val' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3670.334006126435!2d72.53589!3d23.0844782!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e8346f33cf22f%3A0xea4aaec441b80ab1!2sGanesh%20Glory%2011!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>', 'type' => 'html', 'label' => 'Google Maps Embed Code'],
        ];

        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data['val'],
                    'type' => $data['type'],
                    'label' => $data['label'],
                    'group' => 'general'
                ]
            );
        }
    }
}
