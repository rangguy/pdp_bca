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
        Schema::create('pengajuan_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idpengajuan')->constrained('pengajuan_kredits')->cascadeOnDelete();
            $table->enum('jenis_dokumen', ['KTP', 'BUKTI_BAYAR', 'FORM_APLIKASI', 'KARTU_KELUARGA', 'KONTRAK', 'PO']);
            $table->string('file_path', 255);
            $table->boolean('is_generated')->default(false);
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_dokumens');
    }
};
