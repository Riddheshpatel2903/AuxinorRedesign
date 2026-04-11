<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\PageSection::where('section_key', 'home_products')->first();
if ($s) {
    $st = $s->styles ?? [];
    unset($st['base']);
    unset($st['el_style_auto_117_div_1']);
    unset($st['backgroundColor']);
    $s->styles = $st;
    $s->save();
    echo "Styles Cleared!\n";
} else {
    echo "Section not found.\n";
}
