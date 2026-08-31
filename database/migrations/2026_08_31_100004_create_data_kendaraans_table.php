<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_kendaraans', function (Blueprint $table) {
            $table->foreignId('idpengajuan')->primary()->constrained('pengajuan_kredits')->cascadeOnDelete();
            $table->string('merk', 50);
            $table->string('model', 50);
            $table->string('tipe', 50);
            $table->string('warna', 30);
            $table->decimal('harga_kendaraan', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kendaraans');
    }
};
