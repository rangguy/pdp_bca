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
        Schema::create('data_konsumens', function (Blueprint $table) {
            $table->foreignId('idpengajuan')->primary()->constrained('pengajuan_kredits')->cascadeOnDelete();
            $table->string('nama', 150);
            $table->string('nik', 16);
            $table->date('tanggal_lahir');
            $table->enum('status_perkawinan', ['BELUM_KAWIN', 'KAWIN', 'CERAI']);
            $table->string('nama_pasangan', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_konsumens');
    }
};
