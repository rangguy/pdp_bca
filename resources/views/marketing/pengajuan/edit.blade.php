@extends('layouts.app')

@section('title', 'Edit Pengajuan ' . $pengajuan->kode_pengajuan)
@section('heading', 'Edit Pengajuan')

@section('content')
<form method="POST" action="{{ route('marketing.pengajuan.update', $pengajuan) }}" enctype="multipart/form-data" class="space-y-8 max-w-4xl">
    @csrf
    @method('PUT')

    @if($errors->any())
    <div class="px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($pengajuan->status === \App\Enums\StatusPengajuan::Ditolak && $pengajuan->catatan_reject)
    <div class="px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        <p class="font-semibold mb-1">Alasan Penolakan Sebelumnya:</p>
        <p>{{ $pengajuan->catatan_reject }}</p>
        <p class="mt-2 text-xs">Revisi data di bawah, lalu simpan untuk mengubah status kembali ke Draft.</p>
    </div>
    @endif

    {{-- Dealer Selection --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">1</span>
            Pilih Dealer
        </h3>
        <select id="iddealer" name="iddealer" required class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            @foreach($dealers as $dealer)
                <option value="{{ $dealer->id }}" {{ old('iddealer', $pengajuan->iddealer) == $dealer->id ? 'selected' : '' }}>{{ $dealer->nama_dealer }} — {{ $dealer->alamat }}</option>
            @endforeach
        </select>
    </div>

    {{-- Data Konsumen --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">2</span>
            Data Konsumen
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="konsumen_nama" class="block text-sm font-medium text-surface-200/70 mb-1.5">Nama Lengkap</label>
                <input id="konsumen_nama" type="text" name="konsumen_nama" value="{{ old('konsumen_nama', $pengajuan->konsumen?->nama) }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            </div>
            <div>
                <label for="konsumen_nik" class="block text-sm font-medium text-surface-200/70 mb-1.5">NIK (16 digit)</label>
                <input id="konsumen_nik" type="text" name="konsumen_nik" value="{{ old('konsumen_nik', $pengajuan->konsumen?->nik) }}" required maxlength="16" pattern="\d{16}"
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white font-mono">
            </div>
            <div>
                <label for="konsumen_tanggal_lahir" class="block text-sm font-medium text-surface-200/70 mb-1.5">Tanggal Lahir</label>
                <input id="konsumen_tanggal_lahir" type="date" name="konsumen_tanggal_lahir" value="{{ old('konsumen_tanggal_lahir', $pengajuan->konsumen?->tanggal_lahir?->format('Y-m-d')) }}" required max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            </div>
            <div x-data="{ status: '{{ old('konsumen_status_perkawinan', $pengajuan->konsumen?->status_perkawinan?->value ?? '') }}' }">
                <label for="konsumen_status_perkawinan" class="block text-sm font-medium text-surface-200/70 mb-1.5">Status Perkawinan</label>
                <select id="konsumen_status_perkawinan" name="konsumen_status_perkawinan" required x-model="status"
                        class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
                    @foreach(\App\Enums\StatusPerkawinan::cases() as $sp)
                        <option value="{{ $sp->value }}">{{ $sp->label() }}</option>
                    @endforeach
                </select>
                <div x-show="status === 'KAWIN'" x-cloak class="mt-3">
                    <label for="konsumen_nama_pasangan" class="block text-sm font-medium text-surface-200/70 mb-1.5">Nama Pasangan</label>
                    <input id="konsumen_nama_pasangan" type="text" name="konsumen_nama_pasangan" value="{{ old('konsumen_nama_pasangan', $pengajuan->konsumen?->nama_pasangan) }}"
                           class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
                </div>
            </div>
        </div>
    </div>

    {{-- Data Kendaraan --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">3</span>
            Data Kendaraan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="kendaraan_merk" class="block text-sm font-medium text-surface-200/70 mb-1.5">Merk</label>
                <input id="kendaraan_merk" type="text" name="kendaraan_merk" value="{{ old('kendaraan_merk', $pengajuan->kendaraan?->merk) }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            </div>
            <div>
                <label for="kendaraan_model" class="block text-sm font-medium text-surface-200/70 mb-1.5">Model</label>
                <input id="kendaraan_model" type="text" name="kendaraan_model" value="{{ old('kendaraan_model', $pengajuan->kendaraan?->model) }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            </div>
            <div>
                <label for="kendaraan_tipe" class="block text-sm font-medium text-surface-200/70 mb-1.5">Tipe</label>
                <input id="kendaraan_tipe" type="text" name="kendaraan_tipe" value="{{ old('kendaraan_tipe', $pengajuan->kendaraan?->tipe) }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            </div>
            <div>
                <label for="kendaraan_warna" class="block text-sm font-medium text-surface-200/70 mb-1.5">Warna</label>
                <input id="kendaraan_warna" type="text" name="kendaraan_warna" value="{{ old('kendaraan_warna', $pengajuan->kendaraan?->warna) }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            </div>
            <div class="md:col-span-2">
                <label for="kendaraan_harga" class="block text-sm font-medium text-surface-200/70 mb-1.5">Harga Kendaraan (Rp)</label>
                <input id="kendaraan_harga" type="number" name="kendaraan_harga" value="{{ old('kendaraan_harga', $pengajuan->kendaraan?->harga_kendaraan) }}" required min="0" step="1000"
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white font-mono">
            </div>
        </div>
    </div>

    {{-- Data Pinjaman --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6" x-data="pinjamanCalc()">
        <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">4</span>
            Data Pinjaman
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="pinjaman_asuransi" class="block text-sm font-medium text-surface-200/70 mb-1.5">Asuransi</label>
                <input id="pinjaman_asuransi" type="text" name="pinjaman_asuransi" value="{{ old('pinjaman_asuransi', $pengajuan->pinjaman?->asuransi) }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            </div>
            <div>
                <label for="pinjaman_dp" class="block text-sm font-medium text-surface-200/70 mb-1.5">Down Payment (Rp)</label>
                <input id="pinjaman_dp" type="number" name="pinjaman_dp" value="{{ old('pinjaman_dp', $pengajuan->pinjaman?->down_payment) }}" required min="0" step="1000"
                       x-model="dp" @input="calcAngsuran()"
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white font-mono">
            </div>
            <div>
                <label for="pinjaman_tenor" class="block text-sm font-medium text-surface-200/70 mb-1.5">Tenor (bulan)</label>
                <select id="pinjaman_tenor" name="pinjaman_tenor" required x-model="tenor" @change="calcAngsuran()"
                        class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
                    @foreach([12, 24, 36, 48, 60] as $t)
                        <option value="{{ $t }}" {{ old('pinjaman_tenor', $pengajuan->pinjaman?->lama_kredit_bulan) == $t ? 'selected' : '' }}>{{ $t }} bulan</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-surface-200/70 mb-1.5">Angsuran / Bulan (otomatis)</label>
                <div class="w-full px-4 py-2.5 bg-surface-800/50 border border-surface-700/50 rounded-xl text-emerald-400 font-mono font-semibold">
                    Rp <span x-text="angsuranFormatted">{{ number_format($pengajuan->pinjaman?->angsuran_per_bulan ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Upload Dokumen --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">5</span>
            Upload Dokumen
        </h3>
        <p class="text-xs text-surface-200/50 mb-4">Upload ulang untuk mengganti dokumen yang sudah ada.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php $uploadedTypes = $pengajuan->dokumens->pluck('jenis_dokumen')->map(fn($j) => $j->value)->toArray(); @endphp
            @foreach(['dokumen_ktp' => ['KTP', 'KTP'], 'dokumen_bukti_bayar' => ['Bukti Bayar', 'BUKTI_BAYAR'], 'dokumen_form_aplikasi' => ['Form Aplikasi', 'FORM_APLIKASI'], 'dokumen_kartu_keluarga' => ['Kartu Keluarga', 'KARTU_KELUARGA']] as $name => [$label, $type])
            <div>
                <label for="{{ $name }}" class="block text-sm font-medium text-surface-200/70 mb-1.5">
                    {{ $label }}
                    @if(in_array($type, $uploadedTypes))
                        <span class="text-emerald-400 text-xs ml-1">✓ Sudah diupload</span>
                    @endif
                </label>
                <input id="{{ $name }}" type="file" name="{{ $name }}" accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full text-sm text-surface-200/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-600/20 file:text-brand-400 hover:file:bg-brand-600/30 cursor-pointer">
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-3 justify-end">
        <a href="{{ route('marketing.pengajuan.show', $pengajuan) }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-surface-200/60 hover:text-white bg-surface-800 border border-surface-700">Batal</a>
        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-brand-600 to-brand-500 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/25 text-sm">Simpan Perubahan</button>
    </div>
</form>

<script>
function pinjamanCalc() {
    return {
        dp: '{{ old("pinjaman_dp", $pengajuan->pinjaman?->down_payment ?? "") }}',
        tenor: '{{ old("pinjaman_tenor", $pengajuan->pinjaman?->lama_kredit_bulan ?? "") }}',
        angsuranFormatted: '{{ number_format($pengajuan->pinjaman?->angsuran_per_bulan ?? 0, 0, ",", ".") }}',
        calcAngsuran() {
            const harga = parseFloat(document.getElementById('kendaraan_harga')?.value || 0);
            const dp = parseFloat(this.dp || 0);
            const tenor = parseInt(this.tenor || 0);
            if (harga > 0 && tenor > 0 && dp < harga) {
                this.angsuranFormatted = Math.round((harga - dp) / tenor).toLocaleString('id-ID');
            } else {
                this.angsuranFormatted = '0';
            }
        }
    }
}
</script>
@endsection
