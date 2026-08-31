<?php

namespace App\Services;

use App\Enums\JenisDokumen;
use App\Models\PengajuanDokumen;
use App\Models\PengajuanKredit;
use Illuminate\Support\Facades\Storage;

class DokumenGeneratorService
{
    /**
     * Generate dokumen kontrak dan PO sederhana (HTML → PDF-like .html file) untuk MVP.
     * Mengisi data dari pengajuan ke template HTML sederhana.
     */
    public function generate(PengajuanKredit $pengajuan): void
    {
        $pengajuan->load(['konsumen', 'kendaraan', 'pinjaman', 'dealer', 'marketing']);

        $kontrakPath = $this->generateKontrak($pengajuan);
        $poPath = $this->generatePO($pengajuan);

        PengajuanDokumen::create([
            'idpengajuan' => $pengajuan->id,
            'jenis_dokumen' => JenisDokumen::Kontrak,
            'file_path' => $kontrakPath,
            'is_generated' => true,
            'uploaded_at' => now(),
        ]);

        PengajuanDokumen::create([
            'idpengajuan' => $pengajuan->id,
            'jenis_dokumen' => JenisDokumen::Po,
            'file_path' => $poPath,
            'is_generated' => true,
            'uploaded_at' => now(),
        ]);
    }

    private function generateKontrak(PengajuanKredit $pengajuan): string
    {
        $html = view('dokumen.kontrak', compact('pengajuan'))->render();
        $filename = "dokumen/kontrak_{$pengajuan->kode_pengajuan}.html";
        Storage::disk('public')->put($filename, $html);

        return $filename;
    }

    private function generatePO(PengajuanKredit $pengajuan): string
    {
        $html = view('dokumen.po', compact('pengajuan'))->render();
        $filename = "dokumen/po_{$pengajuan->kode_pengajuan}.html";
        Storage::disk('public')->put($filename, $html);

        return $filename;
    }
}
