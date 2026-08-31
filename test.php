<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\PengajuanKredit::find(4);
$uploadedTypes = $p->dokumens()->pluck('jenis_dokumen')->toArray();
$requiredTypes = array_map(fn ($t) => $t->value, App\Enums\JenisDokumen::requiredUploads());
$missing = array_diff($requiredTypes, $uploadedTypes);

print_r([
    'uploaded' => $uploadedTypes,
    'required' => $requiredTypes,
    'missing' => $missing,
]);
