<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKredit;
use App\Services\DokumenGeneratorService;
use App\Services\PengajuanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DokumenController extends Controller
{
    public function __construct(
        private PengajuanService $pengajuanService,
        private DokumenGeneratorService $dokumenGenerator,
    ) {}

    public function index(): View
    {
        $pengajuans = PengajuanKredit::whereIn('status', ['DISETUJUI', 'DOKUMEN_SIAP'])
            ->with(['marketing', 'dealer', 'konsumen'])
            ->orderByDesc('tanggal_approval')
            ->paginate(10);

        return view('backoffice.dokumen.index', compact('pengajuans'));
    }

    public function show(PengajuanKredit $pengajuan): View
    {
        $pengajuan->load(['konsumen', 'kendaraan', 'pinjaman', 'dealer', 'marketing', 'dokumens', 'histories.user']);

        return view('backoffice.dokumen.show', compact('pengajuan'));
    }

    public function generate(Request $request, PengajuanKredit $pengajuan): RedirectResponse
    {
        if (! $pengajuan->isGeneratable()) {
            return back()->with('error', 'Dokumen hanya bisa di-generate untuk pengajuan yang sudah disetujui.');
        }

        $this->dokumenGenerator->generate($pengajuan);
        $this->pengajuanService->generateDokumen($pengajuan, $request->user()->id);

        return redirect()->route('backoffice.dokumen.show', $pengajuan)
            ->with('success', 'Dokumen kontrak dan PO berhasil di-generate.');
    }
}
