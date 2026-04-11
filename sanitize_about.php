<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PageSection;

$section = PageSection::where('page_slug', 'home')
    ->where('section_key', 'home_about')
    ->first();

if ($section) {
    echo "Found section ID: {$section->id}. Current items in content: " . count($section->content ?? []) . "\n";
    
    // Sanitize: only keep simple keys, remove anything that looks like full HTML blocks
    $cleanContent = [];
    $dirty = false;
    foreach (($section->content ?? []) as $key => $val) {
        if (strlen($val) > 1000 || str_contains($val, '<section') || str_contains($val, '<div')) {
            echo "Removing potentially corrupt key: $key\n";
            $dirty = true;
            continue;
        }
        $cleanContent[$key] = $val;
    }
    
    if ($dirty || empty($section->content)) {
        $section->content = $cleanContent;
        $section->save();
        echo "Section content sanitized.\n";
    } else {
        echo "Section content was already clean.\n";
    }
} else {
    echo "Section home_about not found in database.\n";
}
