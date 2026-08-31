<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pengajuan = App\Models\PengajuanKredit::find(4);
$uploadedTypes = $pengajuan->dokumens()->pluck('jenis_dokumen')->map(fn ($jenis) => $jenis instanceof App\Enums\JenisDokumen ? $jenis->value : $jenis)->toArray();
$requiredTypes = array_map(fn ($t) => $t->value, App\Enums\JenisDokumen::requiredUploads());
$missing = array_diff($requiredTypes, $uploadedTypes);

print_r([
    'uploadedTypes' => $uploadedTypes,
    'requiredTypes' => $requiredTypes,
    'missing' => $missing,
]);

echo "\nSubmitting...\n";
try {
    app(App\Services\PengajuanService::class)->submit($pengajuan, 1);
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
