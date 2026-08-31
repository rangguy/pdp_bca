<?php

namespace App\Http\Controllers\Marketing;

use App\Enums\JenisDokumen;
use App\Enums\StatusPengajuan;
use App\Http\Controllers\Controller;
use App\Models\MasterDealer;
use App\Models\PengajuanKredit;
use App\Services\PengajuanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    public function __construct(
        private PengajuanService $pengajuanService,
    ) {}

    public function index(Request $request): View
    {
        $query = PengajuanKredit::where('iduser_marketing', $request->user()->id)
            ->with(['dealer', 'konsumen'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $pengajuans = $query->paginate(10);

        return view('marketing.pengajuan.index', compact('pengajuans'));
    }

    public function create(): View
    {
        $dealers = MasterDealer::orderBy('nama_dealer')->get();

        return view('marketing.pengajuan.create', compact('dealers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'iddealer' => ['required', 'exists:master_dealers,id'],
            // Konsumen
            'konsumen_nama' => ['required', 'string', 'max:150'],
            'konsumen_nik' => ['required', 'string', 'size:16', 'regex:/^\d{16}$/'],
            'konsumen_tanggal_lahir' => ['required', 'date', 'before:today'],
            'konsumen_status_perkawinan' => ['required', 'in:BELUM_KAWIN,KAWIN,CERAI'],
            'konsumen_nama_pasangan' => ['required_if:konsumen_status_perkawinan,KAWIN', 'nullable', 'string', 'max:150'],
            // Kendaraan
            'kendaraan_merk' => ['required', 'string', 'max:50'],
            'kendaraan_model' => ['required', 'string', 'max:50'],
            'kendaraan_tipe' => ['required', 'string', 'max:50'],
            'kendaraan_warna' => ['required', 'string', 'max:30'],
            'kendaraan_harga' => ['required', 'numeric', 'min:1'],
            // Pinjaman
            'pinjaman_asuransi' => ['required', 'string', 'max:50'],
            'pinjaman_dp' => ['required', 'numeric', 'min:0'],
            'pinjaman_tenor' => ['required', 'in:12,24,36,48,60'],
            // Dokumen (optional saat draft)
            'dokumen_ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'dokumen_bukti_bayar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'dokumen_form_aplikasi' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'dokumen_kartu_keluarga' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        // Validasi DP < harga kendaraan
        if ((float) $validated['pinjaman_dp'] >= (float) $validated['kendaraan_harga']) {
            return back()->withErrors(['pinjaman_dp' => 'Down payment harus kurang dari harga kendaraan.'])->withInput();
        }

        $angsuran = $this->pengajuanService->hitungAngsuran(
            (float) $validated['kendaraan_harga'],
            (float) $validated['pinjaman_dp'],
            (int) $validated['pinjaman_tenor']
        );

        $pengajuan = DB::transaction(function () use ($request, $validated, $angsuran) {
            $pengajuan = PengajuanKredit::create([
                'kode_pengajuan' => $this->pengajuanService->generateKodePengajuan(),
                'iduser_marketing' => $request->user()->id,
                'iddealer' => $validated['iddealer'],
                'status' => StatusPengajuan::Draft,
            ]);

            $pengajuan->konsumen()->create([
                'nama' => $validated['konsumen_nama'],
                'nik' => $validated['konsumen_nik'],
                'tanggal_lahir' => $validated['konsumen_tanggal_lahir'],
                'status_perkawinan' => $validated['konsumen_status_perkawinan'],
                'nama_pasangan' => $validated['konsumen_nama_pasangan'] ?? null,
            ]);

            $pengajuan->kendaraan()->create([
                'merk' => $validated['kendaraan_merk'],
                'model' => $validated['kendaraan_model'],
                'tipe' => $validated['kendaraan_tipe'],
                'warna' => $validated['kendaraan_warna'],
                'harga_kendaraan' => $validated['kendaraan_harga'],
            ]);

            $pengajuan->pinjaman()->create([
                'asuransi' => $validated['pinjaman_asuransi'],
                'down_payment' => $validated['pinjaman_dp'],
                'lama_kredit_bulan' => $validated['pinjaman_tenor'],
                'angsuran_per_bulan' => $angsuran,
            ]);

            // Upload dokumen
            $this->uploadDokumens($request, $pengajuan);

            // Tulis history: create
            $pengajuan->histories()->create([
                'status_sebelum' => null,
                'status_sesudah' => StatusPengajuan::Draft->value,
                'iduser' => $request->user()->id,
                'catatan' => 'Pengajuan dibuat',
            ]);

            return $pengajuan;
        });

        return redirect()->route('marketing.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan berhasil dibuat sebagai draft.');
    }

    public function show(Request $request, PengajuanKredit $pengajuan): View
    {
        if ($pengajuan->iduser_marketing !== $request->user()->id) {
            abort(403);
        }

        $pengajuan->load(['konsumen', 'kendaraan', 'pinjaman', 'dealer', 'dokumens', 'histories.user']);

        return view('marketing.pengajuan.show', compact('pengajuan'));
    }

    public function edit(Request $request, PengajuanKredit $pengajuan): View
    {
        if ($pengajuan->iduser_marketing !== $request->user()->id) {
            abort(403);
        }

        if (! $pengajuan->isEditable()) {
            abort(403, 'Pengajuan tidak bisa diedit pada status ini.');
        }

        $pengajuan->load(['konsumen', 'kendaraan', 'pinjaman', 'dokumens']);
        $dealers = MasterDealer::orderBy('nama_dealer')->get();

        return view('marketing.pengajuan.edit', compact('pengajuan', 'dealers'));
    }

    public function update(Request $request, PengajuanKredit $pengajuan): RedirectResponse
    {
        if ($pengajuan->iduser_marketing !== $request->user()->id || ! $pengajuan->isEditable()) {
            abort(403);
        }

        $validated = $request->validate([
            'iddealer' => ['required', 'exists:master_dealers,id'],
            'konsumen_nama' => ['required', 'string', 'max:150'],
            'konsumen_nik' => ['required', 'string', 'size:16', 'regex:/^\d{16}$/'],
            'konsumen_tanggal_lahir' => ['required', 'date', 'before:today'],
            'konsumen_status_perkawinan' => ['required', 'in:BELUM_KAWIN,KAWIN,CERAI'],
            'konsumen_nama_pasangan' => ['required_if:konsumen_status_perkawinan,KAWIN', 'nullable', 'string', 'max:150'],
            'kendaraan_merk' => ['required', 'string', 'max:50'],
            'kendaraan_model' => ['required', 'string', 'max:50'],
            'kendaraan_tipe' => ['required', 'string', 'max:50'],
            'kendaraan_warna' => ['required', 'string', 'max:30'],
            'kendaraan_harga' => ['required', 'numeric', 'min:1'],
            'pinjaman_asuransi' => ['required', 'string', 'max:50'],
            'pinjaman_dp' => ['required', 'numeric', 'min:0'],
            'pinjaman_tenor' => ['required', 'in:12,24,36,48,60'],
            'dokumen_ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'dokumen_bukti_bayar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'dokumen_form_aplikasi' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'dokumen_kartu_keluarga' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        if ((float) $validated['pinjaman_dp'] >= (float) $validated['kendaraan_harga']) {
            return back()->withErrors(['pinjaman_dp' => 'Down payment harus kurang dari harga kendaraan.'])->withInput();
        }

        $angsuran = $this->pengajuanService->hitungAngsuran(
            (float) $validated['kendaraan_harga'],
            (float) $validated['pinjaman_dp'],
            (int) $validated['pinjaman_tenor']
        );

        DB::transaction(function () use ($pengajuan, $request, $validated, $angsuran) {
            // Jika status DITOLAK, ubah kembali ke DRAFT
            if ($pengajuan->status === StatusPengajuan::Ditolak) {
                $this->pengajuanService->revisi($pengajuan, $request->user()->id);
            }

            $pengajuan->update(['iddealer' => $validated['iddealer']]);

            $pengajuan->konsumen()->updateOrCreate(
                ['idpengajuan' => $pengajuan->id],
                [
                    'nama' => $validated['konsumen_nama'],
                    'nik' => $validated['konsumen_nik'],
                    'tanggal_lahir' => $validated['konsumen_tanggal_lahir'],
                    'status_perkawinan' => $validated['konsumen_status_perkawinan'],
                    'nama_pasangan' => $validated['konsumen_nama_pasangan'] ?? null,
                ]
            );

            $pengajuan->kendaraan()->updateOrCreate(
                ['idpengajuan' => $pengajuan->id],
                [
                    'merk' => $validated['kendaraan_merk'],
                    'model' => $validated['kendaraan_model'],
                    'tipe' => $validated['kendaraan_tipe'],
                    'warna' => $validated['kendaraan_warna'],
                    'harga_kendaraan' => $validated['kendaraan_harga'],
                ]
            );

            $pengajuan->pinjaman()->updateOrCreate(
                ['idpengajuan' => $pengajuan->id],
                [
                    'asuransi' => $validated['pinjaman_asuransi'],
                    'down_payment' => $validated['pinjaman_dp'],
                    'lama_kredit_bulan' => $validated['pinjaman_tenor'],
                    'angsuran_per_bulan' => $angsuran,
                ]
            );

            $this->uploadDokumens($request, $pengajuan);
        });

        return redirect()->route('marketing.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function submit(Request $request, PengajuanKredit $pengajuan): RedirectResponse
    {
        if ($pengajuan->iduser_marketing !== $request->user()->id) {
            abort(403);
        }

        if (! $pengajuan->isSubmittable()) {
            return back()->with('error', 'Pengajuan hanya bisa disubmit dari status Draft.');
        }

        // Validasi kelengkapan data
        if (! $pengajuan->konsumen || ! $pengajuan->kendaraan || ! $pengajuan->pinjaman) {
            return back()->with('error', 'Data pengajuan belum lengkap.');
        }

        // Validasi kelengkapan dokumen wajib
        $uploadedTypes = $pengajuan->dokumens()->pluck('jenis_dokumen')->map(fn ($jenis) => $jenis instanceof JenisDokumen ? $jenis->value : $jenis)->toArray();
        $requiredTypes = array_map(fn ($t) => $t->value, JenisDokumen::requiredUploads());
        $missing = array_diff($requiredTypes, $uploadedTypes);

        if (! empty($missing)) {
            $missingLabels = collect($missing)->map(fn ($v) => JenisDokumen::from($v)->label())->join(', ');

            return back()->with('error', "Dokumen wajib belum lengkap: {$missingLabels}");
        }

        $this->pengajuanService->submit($pengajuan, $request->user()->id);

        return redirect()->route('marketing.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan berhasil disubmit untuk approval.');
    }

    private function uploadDokumens(Request $request, PengajuanKredit $pengajuan): void
    {
        $dokumenMap = [
            'dokumen_ktp' => JenisDokumen::Ktp,
            'dokumen_bukti_bayar' => JenisDokumen::BuktiBayar,
            'dokumen_form_aplikasi' => JenisDokumen::FormAplikasi,
            'dokumen_kartu_keluarga' => JenisDokumen::KartuKeluarga,
        ];

        foreach ($dokumenMap as $inputName => $jenis) {
            if ($request->hasFile($inputName)) {
                $path = $request->file($inputName)->store("pengajuan/{$pengajuan->id}", 'public');

                // Replace existing document of same type
                $pengajuan->dokumens()->where('jenis_dokumen', $jenis->value)->delete();

                $pengajuan->dokumens()->create([
                    'jenis_dokumen' => $jenis,
                    'file_path' => $path,
                    'is_generated' => false,
                    'uploaded_at' => now(),
                ]);
            }
        }
    }
}
