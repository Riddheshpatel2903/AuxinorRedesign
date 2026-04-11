<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PageSection;

$sections = PageSection::where('page_slug', 'home')
    ->where('section_key', 'home_about')
    ->get();

echo "Found " . $sections->count() . " home_about sections.\n";

if ($sections->count() > 1) {
    $toKeep = $sections->first();
    echo "Keeping ID: {$toKeep->id}\n";
    foreach ($sections->skip(1) as $dup) {
        echo "Deleting Duplicate ID: {$dup->id}\n";
        $dup->delete();
    }
} else {
    echo "No duplicates found.\n";
}
