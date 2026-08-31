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
        Schema::create('pengajuan_kredits', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan', 30)->unique();
            $table->foreignId('iduser_marketing')->constrained('users');
            $table->foreignId('iddealer')->constrained('master_dealers');
            $table->enum('status', ['DRAFT', 'MENUNGGU_APPROVAL', 'DISETUJUI', 'DITOLAK', 'DOKUMEN_SIAP'])->default('DRAFT');
            $table->text('catatan_reject')->nullable();
            $table->foreignId('iduser_approval')->nullable()->constrained('users');
            $table->dateTime('tanggal_submit')->nullable();
            $table->dateTime('tanggal_approval')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kredits');
    }
};
