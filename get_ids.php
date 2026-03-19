<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\PageSection::where('page_slug', 'about')->get() as $s) {
    echo $s->id . ':' . $s->section_key . "\n";
}
