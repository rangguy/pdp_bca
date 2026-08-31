@extends('layouts.app')

@section('title', 'Buat Pengajuan Baru')
@section('heading', 'Buat Pengajuan Baru')

@section('content')
<form method="POST" action="{{ route('marketing.pengajuan.store') }}" enctype="multipart/form-data" class="space-y-8 max-w-4xl">
    @csrf

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Dealer Selection --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center">1</span>
            Pilih Dealer
        </h3>
        <div>
            <label for="iddealer" class="block text-sm font-medium text-surface-200/70 mb-1.5">Dealer</label>
            <select id="iddealer" name="iddealer" required class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
                <option value="">— Pilih dealer —</option>
                @foreach($dealers as $dealer)
                    <option value="{{ $dealer->id }}" {{ old('iddealer') == $dealer->id ? 'selected' : '' }}>{{ $dealer->nama_dealer }} — {{ $dealer->alamat }}</option>
                @endforeach
            </select>
        </div>
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
                <input id="konsumen_nama" type="text" name="konsumen_nama" value="{{ old('konsumen_nama') }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30" placeholder="Nama lengkap konsumen">
            </div>
            <div>
                <label for="konsumen_nik" class="block text-sm font-medium text-surface-200/70 mb-1.5">NIK (16 digit)</label>
                <input id="konsumen_nik" type="text" name="konsumen_nik" value="{{ old('konsumen_nik') }}" required maxlength="16" pattern="\d{16}"
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30 font-mono" placeholder="3201xxxxxxxxxx">
            </div>
            <div>
                <label for="konsumen_tanggal_lahir" class="block text-sm font-medium text-surface-200/70 mb-1.5">Tanggal Lahir</label>
                <input id="konsumen_tanggal_lahir" type="date" name="konsumen_tanggal_lahir" value="{{ old('konsumen_tanggal_lahir') }}" required max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
            </div>
            <div x-data="{ status: '{{ old('konsumen_status_perkawinan', '') }}' }">
                <label for="konsumen_status_perkawinan" class="block text-sm font-medium text-surface-200/70 mb-1.5">Status Perkawinan</label>
                <select id="konsumen_status_perkawinan" name="konsumen_status_perkawinan" required x-model="status"
                        class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
                    <option value="">— Pilih —</option>
                    @foreach(\App\Enums\StatusPerkawinan::cases() as $sp)
                        <option value="{{ $sp->value }}">{{ $sp->label() }}</option>
                    @endforeach
                </select>
                <div x-show="status === 'KAWIN'" x-cloak class="mt-3">
                    <label for="konsumen_nama_pasangan" class="block text-sm font-medium text-surface-200/70 mb-1.5">Nama Pasangan</label>
                    <input id="konsumen_nama_pasangan" type="text" name="konsumen_nama_pasangan" value="{{ old('konsumen_nama_pasangan') }}"
                           class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30" placeholder="Nama pasangan">
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
                <input id="kendaraan_merk" type="text" name="kendaraan_merk" value="{{ old('kendaraan_merk') }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30" placeholder="Honda, Toyota, dll">
            </div>
            <div>
                <label for="kendaraan_model" class="block text-sm font-medium text-surface-200/70 mb-1.5">Model</label>
                <input id="kendaraan_model" type="text" name="kendaraan_model" value="{{ old('kendaraan_model') }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30" placeholder="Brio, Avanza, dll">
            </div>
            <div>
                <label for="kendaraan_tipe" class="block text-sm font-medium text-surface-200/70 mb-1.5">Tipe</label>
                <input id="kendaraan_tipe" type="text" name="kendaraan_tipe" value="{{ old('kendaraan_tipe') }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30" placeholder="1.2 RS CVT">
            </div>
            <div>
                <label for="kendaraan_warna" class="block text-sm font-medium text-surface-200/70 mb-1.5">Warna</label>
                <input id="kendaraan_warna" type="text" name="kendaraan_warna" value="{{ old('kendaraan_warna') }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30" placeholder="Putih, Hitam, dll">
            </div>
            <div class="md:col-span-2">
                <label for="kendaraan_harga" class="block text-sm font-medium text-surface-200/70 mb-1.5">Harga Kendaraan (Rp)</label>
                <input id="kendaraan_harga" type="number" name="kendaraan_harga" value="{{ old('kendaraan_harga') }}" required min="0" step="1000"
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30 font-mono" placeholder="250000000">
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
                <input id="pinjaman_asuransi" type="text" name="pinjaman_asuransi" value="{{ old('pinjaman_asuransi') }}" required
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30" placeholder="Nama asuransi">
            </div>
            <div>
                <label for="pinjaman_dp" class="block text-sm font-medium text-surface-200/70 mb-1.5">Down Payment (Rp)</label>
                <input id="pinjaman_dp" type="number" name="pinjaman_dp" value="{{ old('pinjaman_dp') }}" required min="0" step="1000"
                       x-model="dp" @input="calcAngsuran()"
                       class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30 font-mono" placeholder="50000000">
            </div>
            <div>
                <label for="pinjaman_tenor" class="block text-sm font-medium text-surface-200/70 mb-1.5">Tenor (bulan)</label>
                <select id="pinjaman_tenor" name="pinjaman_tenor" required x-model="tenor" @change="calcAngsuran()"
                        class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white">
                    <option value="">— Pilih tenor —</option>
                    <option value="12" {{ old('pinjaman_tenor') == '12' ? 'selected' : '' }}>12 bulan</option>
                    <option value="24" {{ old('pinjaman_tenor') == '24' ? 'selected' : '' }}>24 bulan</option>
                    <option value="36" {{ old('pinjaman_tenor') == '36' ? 'selected' : '' }}>36 bulan</option>
                    <option value="48" {{ old('pinjaman_tenor') == '48' ? 'selected' : '' }}>48 bulan</option>
                    <option value="60" {{ old('pinjaman_tenor') == '60' ? 'selected' : '' }}>60 bulan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-surface-200/70 mb-1.5">Angsuran / Bulan (otomatis)</label>
                <div class="w-full px-4 py-2.5 bg-surface-800/50 border border-surface-700/50 rounded-xl text-emerald-400 font-mono font-semibold">
                    Rp <span x-text="angsuranFormatted">0</span>
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
        <p class="text-xs text-surface-200/50 mb-4">Format: JPG, PNG, PDF. Maks 5MB per file. Semua dokumen wajib diupload sebelum submit.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach(['dokumen_ktp' => 'KTP', 'dokumen_bukti_bayar' => 'Bukti Bayar Tanda Jadi', 'dokumen_form_aplikasi' => 'Form Aplikasi Pengajuan', 'dokumen_kartu_keluarga' => 'Kartu Keluarga'] as $name => $label)
            <div>
                <label for="{{ $name }}" class="block text-sm font-medium text-surface-200/70 mb-1.5">{{ $label }}</label>
                <input id="{{ $name }}" type="file" name="{{ $name }}" accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full text-sm text-surface-200/60 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-600/20 file:text-brand-400 hover:file:bg-brand-600/30 cursor-pointer">
            </div>
            @endforeach
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 justify-end">
        <a href="{{ route('marketing.pengajuan.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-surface-200/60 hover:text-white bg-surface-800 border border-surface-700 hover:border-surface-600">
            Batal
        </a>
        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/25 text-sm transform hover:-translate-y-0.5">
            Simpan sebagai Draft
        </button>
    </div>
</form>

<script>
function pinjamanCalc() {
    return {
        dp: '{{ old("pinjaman_dp", "") }}',
        tenor: '{{ old("pinjaman_tenor", "") }}',
        angsuranFormatted: '0',
        calcAngsuran() {
            const harga = parseFloat(document.getElementById('kendaraan_harga')?.value || 0);
            const dp = parseFloat(this.dp || 0);
            const tenor = parseInt(this.tenor || 0);
            if (harga > 0 && tenor > 0 && dp < harga) {
                const angsuran = (harga - dp) / tenor;
                this.angsuranFormatted = Math.round(angsuran).toLocaleString('id-ID');
            } else {
                this.angsuranFormatted = '0';
            }
        }
    }
}
</script>
@endsection
