<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'company_name' => 'Auxinor Chemicals LLP',
            'company_tagline' => 'Trusted Chemical Partners Since 2017',
            'company_established' => '2017',
            'hero_headline_line1' => 'Trusted',
            'hero_headline_line2' => 'Chemical',
            'hero_headline_line3' => 'Partners',
            'hero_subtext' => "Specialists in procurement, bulk distribution, and surplus chemical trading — serving India's top industrial sectors since 2017.",
            'stat_years' => '7',
            'stat_products' => '80',
            'stat_industries' => '6',
            'stat_reach' => 'Pan-India',
            'about_short' => 'Auxinor Chemicals LLP is a B2B chemical trading company based in Ahmedabad, Gujarat. We specialize in the procurement, trading, and distribution of industrial chemicals — including surplus and bulk chemicals.',
            'about_long' => 'Our strength lies in maintaining a strong vendor and sourcing network, providing competitive pricing, and ensuring reliable supply chains for our clients across India.',
            'mission' => 'Our mission is to catalyze global commerce by embodying values and fostering long-term relationships. Grounded in entrepreneurial spirit, we ensure sustained growth through honest, accountable chemical trading.',
            'vision' => 'To be an iconic chemical trading powerhouse, driven by core values and strong connections, leading commerce across India and beyond.',
            'office_address' => 'Ganesh Glory 11, Jagatpur, Gota, Ahmedabad, Gujarat 382481',
            'warehouse_address' => '14 Yogeshwer Estate, Aslali, Ahmedabad, Gujarat',
            'phone_primary' => '+91 990-990-7230',
            'phone_secondary' => '+91 990-990-7231',
            'email_info' => 'info@auxinorchem.com',
            'email_sales' => 'sales@auxinorchem.com',
            'whatsapp_number' => '9909907231',
            'facebook_url' => 'https://www.facebook.com/Auxinor',
            'linkedin_url' => 'https://www.linkedin.com/company/auxinor-chemicals-llp',
            'footer_tagline' => "Chemical trading, distribution, and market intelligence — serving India's industrial sector with trust, accountability, and quality.",
            'google_maps_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3670.334006126435!2d72.53589!3d23.0844782!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e8346f33cf22f%3A0xea4aaec441b80ab1!2sGanesh%20Glory%2011!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'hero_image_url' => 'https://images.unsplash.com/photo-1565688527997-01c3bb6dfe17?w=1200&q=80',
            'about_image_url' => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=1000&q=80',
            'infra_image_url' => 'https://images.unsplash.com/photo-1581093806997-124204d9fa9d?w=1000&q=80',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
