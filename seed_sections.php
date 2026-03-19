<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$homeSections = [
    'hero'=>'Hero Section', 
    'services_strip'=>'Services Strip', 
    'industries'=>'Industries', 
    'products'=>'Products', 
    'about_infra'=>'About & Infrastructure', 
    'insights'=>'Market Insights', 
    'contact'=>'Contact & Enquiry'
];
foreach($homeSections as $key => $label) { 
    \App\Models\PageSection::firstOrCreate(['page_slug'=>'home', 'section_key'=>$key], ['section_label'=>$label, 'sort_order' => 1]); 
}

$aboutSections = [
    'about_hero'=>'About Hero', 
    'about_mission'=>'Mission & Story', 
    'about_values'=>'Core Values', 
    'about_advantage'=>'The Advantage', 
    'about_stats'=>'Trust Stats'
];
foreach($aboutSections as $key => $label) { 
    \App\Models\PageSection::firstOrCreate(['page_slug'=>'about', 'section_key'=>$key], ['section_label'=>$label, 'sort_order' => 1]); 
}
echo "Seeded Page Sections!\n";
