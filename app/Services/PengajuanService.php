<?php

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanHistory;
use App\Models\PengajuanKredit;

class PengajuanService
{
    public function __construct(
        private NotifikasiService $notifikasiService,
    ) {}

    /**
     * Generate kode pengajuan dengan format PGJ-YYYYMMDD-NNNN.
     */
    public function generateKodePengajuan(): string
    {
        $today = now()->format('Ymd');
        $prefix = "PGJ-{$today}-";

        $lastKode = PengajuanKredit::where('kode_pengajuan', 'like', "{$prefix}%")
            ->orderByDesc('kode_pengajuan')
            ->value('kode_pengajuan');

        if ($lastKode) {
            $lastNumber = (int) substr($lastKode, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung angsuran per bulan (flat rate, tanpa bunga).
     * Rumus: (harga_kendaraan - down_payment) / lama_kredit_bulan
     */
    public function hitungAngsuran(float $hargaKendaraan, float $downPayment, int $lamaKreditBulan): float
    {
        if ($lamaKreditBulan <= 0) {
            return 0;
        }

        return round(($hargaKendaraan - $downPayment) / $lamaKreditBulan, 2);
    }

    /**
     * Submit pengajuan: DRAFT → MENUNGGU_APPROVAL.
     */
    public function submit(PengajuanKredit $pengajuan, int $userId): void
    {
        $this->transisiStatus($pengajuan, StatusPengajuan::MenungguApproval, $userId);
        $pengajuan->update(['tanggal_submit' => now()]);
    }

    /**
     * Approve pengajuan: MENUNGGU_APPROVAL → DISETUJUI.
     */
    public function approve(PengajuanKredit $pengajuan, int $userId): void
    {
        $this->transisiStatus($pengajuan, StatusPengajuan::Disetujui, $userId);
        $pengajuan->update([
            'iduser_approval' => $userId,
            'tanggal_approval' => now(),
        ]);
    }

    /**
     * Reject pengajuan: MENUNGGU_APPROVAL → DITOLAK.
     */
    public function reject(PengajuanKredit $pengajuan, int $userId, string $catatan): void
    {
        $pengajuan->update(['catatan_reject' => $catatan, 'iduser_approval' => $userId]);
        $this->transisiStatus($pengajuan, StatusPengajuan::Ditolak, $userId, $catatan);
    }

    /**
     * Generate dokumen: DISETUJUI → DOKUMEN_SIAP.
     */
    public function generateDokumen(PengajuanKredit $pengajuan, int $userId): void
    {
        $this->transisiStatus($pengajuan, StatusPengajuan::DokumenSiap, $userId);
    }

    /**
     * Revisi pengajuan yang ditolak: DITOLAK → DRAFT.
     */
    public function revisi(PengajuanKredit $pengajuan, int $userId): void
    {
        $pengajuan->update(['catatan_reject' => null]);
        $this->transisiStatus($pengajuan, StatusPengajuan::Draft, $userId, 'Pengajuan direvisi');
    }

    private function transisiStatus(
        PengajuanKredit $pengajuan,
        StatusPengajuan $statusBaru,
        int $userId,
        ?string $catatan = null
    ): void {
        $statusLama = $pengajuan->status;

        PengajuanHistory::create([
            'idpengajuan' => $pengajuan->id,
            'status_sebelum' => $statusLama->value,
            'status_sesudah' => $statusBaru->value,
            'iduser' => $userId,
            'catatan' => $catatan,
        ]);

        $pengajuan->update(['status' => $statusBaru]);

        $this->notifikasiService->kirimNotifikasiStatusBerubah($pengajuan, $statusBaru);
    }
}
