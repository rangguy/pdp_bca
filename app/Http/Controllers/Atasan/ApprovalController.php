<?php

namespace App\Http\Controllers\Atasan;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKredit;
use App\Services\PengajuanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function __construct(
        private PengajuanService $pengajuanService,
    ) {}

    public function index(Request $request): View
    {
        $query = PengajuanKredit::where('status', 'MENUNGGU_APPROVAL')
            ->with(['marketing', 'dealer', 'konsumen'])
            ->orderBy('tanggal_submit');

        $pengajuans = $query->paginate(10);

        return view('atasan.approval.index', compact('pengajuans'));
    }

    public function show(PengajuanKredit $pengajuan): View
    {
        if (! $pengajuan->isApprovable()) {
            abort(403, 'Pengajuan tidak dalam status menunggu approval.');
        }

        $pengajuan->load(['konsumen', 'kendaraan', 'pinjaman', 'dealer', 'marketing', 'dokumens', 'histories.user']);

        return view('atasan.approval.show', compact('pengajuan'));
    }

    public function approve(Request $request, PengajuanKredit $pengajuan): RedirectResponse
    {
        if (! $pengajuan->isApprovable()) {
            return back()->with('error', 'Pengajuan tidak bisa di-approve pada status ini.');
        }

        $this->pengajuanService->approve($pengajuan, $request->user()->id);

        return redirect()->route('atasan.approval.index')
            ->with('success', "Pengajuan {$pengajuan->kode_pengajuan} berhasil disetujui.");
    }

    public function reject(Request $request, PengajuanKredit $pengajuan): RedirectResponse
    {
        $validated = $request->validate([
            'catatan_reject' => ['required', 'string', 'min:5'],
        ], [
            'catatan_reject.required' => 'Catatan penolakan wajib diisi.',
            'catatan_reject.min' => 'Catatan penolakan minimal 5 karakter.',
        ]);

        if (! $pengajuan->isApprovable()) {
            return back()->with('error', 'Pengajuan tidak bisa di-reject pada status ini.');
        }

        $this->pengajuanService->reject($pengajuan, $request->user()->id, $validated['catatan_reject']);

        return redirect()->route('atasan.approval.index')
            ->with('success', "Pengajuan {$pengajuan->kode_pengajuan} berhasil ditolak.");
    }
}
