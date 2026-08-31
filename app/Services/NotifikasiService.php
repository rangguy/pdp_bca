<?php

namespace App\Services;

use App\Enums\StatusPengajuan;
use App\Models\Notifikasi;
use App\Models\PengajuanKredit;
use App\Models\User;

class NotifikasiService
{
    /**
     * Kirim notifikasi in-app ke user tertentu.
     */
    public function kirim(int $iduser, string $judul, string $pesan, ?string $link = null): Notifikasi
    {
        return Notifikasi::create([
            'iduser' => $iduser,
            'judul' => $judul,
            'pesan' => $pesan,
            'link' => $link,
        ]);
    }

    /**
     * Kirim notifikasi setelah status pengajuan berubah, sesuai spec bagian 7.
     */
    public function kirimNotifikasiStatusBerubah(PengajuanKredit $pengajuan, StatusPengajuan $statusBaru): void
    {
        $kode = $pengajuan->kode_pengajuan;

        match ($statusBaru) {
            StatusPengajuan::MenungguApproval => $this->notifKeAtasan(
                $pengajuan,
                "Pengajuan Baru: {$kode}",
                "Pengajuan kredit {$kode} telah disubmit dan menunggu approval Anda."
            ),
            StatusPengajuan::Disetujui => $this->notifApproved($pengajuan, $kode),
            StatusPengajuan::Ditolak => $this->kirim(
                $pengajuan->iduser_marketing,
                "Pengajuan Ditolak: {$kode}",
                "Pengajuan kredit {$kode} ditolak. Alasan: {$pengajuan->catatan_reject}",
                route('marketing.pengajuan.edit', $pengajuan)
            ),
            StatusPengajuan::DokumenSiap => $this->kirim(
                $pengajuan->iduser_marketing,
                "Dokumen Siap: {$kode}",
                "Dokumen kontrak & PO untuk pengajuan {$kode} telah berhasil di-generate.",
                route('marketing.pengajuan.show', $pengajuan)
            ),
            default => null,
        };
    }

    private function notifKeAtasan(PengajuanKredit $pengajuan, string $judul, string $pesan): void
    {
        $atasans = User::where('role', 'atasan_marketing')->where('status_user', 'AKTIF')->get();

        foreach ($atasans as $atasan) {
            $this->kirim($atasan->id, $judul, $pesan, route('atasan.approval.show', $pengajuan));
        }
    }

    private function notifApproved(PengajuanKredit $pengajuan, string $kode): void
    {
        // Notif ke Admin Backoffice
        $admins = User::where('role', 'admin_backoffice')->where('status_user', 'AKTIF')->get();
        foreach ($admins as $admin) {
            $this->kirim(
                $admin->id,
                "Pengajuan Disetujui: {$kode}",
                "Pengajuan kredit {$kode} telah disetujui dan siap untuk generate dokumen.",
                route('backoffice.dokumen.show', $pengajuan)
            );
        }

        // Notif ke Marketing pemilik
        $this->kirim(
            $pengajuan->iduser_marketing,
            "Pengajuan Disetujui: {$kode}",
            "Pengajuan kredit {$kode} telah disetujui oleh atasan.",
            route('marketing.pengajuan.show', $pengajuan)
        );
    }
}
