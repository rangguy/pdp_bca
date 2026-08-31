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
        Schema::create('pengajuan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idpengajuan')->constrained('pengajuan_kredits')->cascadeOnDelete();
            $table->string('status_sebelum', 30)->nullable();
            $table->string('status_sesudah', 30);
            $table->foreignId('iduser')->constrained('users');
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_histories');
    }
};
