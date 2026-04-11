<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sections = \App\Models\PageSection::where('page_slug', 'home')->get();
echo "--- ALL HOME SECTIONS (" . $sections->count() . ") ---\n";
foreach($sections as $s) {
    echo "ID: {$s->id} | Key: {$s->section_key} | Label: {$s->section_label}\n";
}
echo "--- END ---\n";
