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
        Schema::create('data_pinjamans', function (Blueprint $table) {
            $table->foreignId('idpengajuan')->primary()->constrained('pengajuan_kredits')->cascadeOnDelete();
            $table->string('asuransi', 50);
            $table->decimal('down_payment', 15, 2);
            $table->integer('lama_kredit_bulan');
            $table->decimal('angsuran_per_bulan', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pinjamans');
    }
};
