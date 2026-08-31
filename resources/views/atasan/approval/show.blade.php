@extends('layouts.app')

@section('title', 'Review Pengajuan ' . $pengajuan->kode_pengajuan)
@section('heading', 'Review Pengajuan')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-white font-mono">{{ $pengajuan->kode_pengajuan }}</h3>
            <p class="text-sm text-surface-200/50 mt-1">Disubmit oleh {{ $pengajuan->marketing->nama_lengkap }} pada {{ $pengajuan->tanggal_submit?->format('d M Y H:i') }}</p>
        </div>
        <a href="{{ route('atasan.approval.index') }}" class="text-sm text-surface-200/50 hover:text-white">← Kembali</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Konsumen --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Konsumen</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Nama</dt><dd class="text-white font-medium">{{ $pengajuan->konsumen?->nama }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">NIK</dt><dd class="text-white font-mono">{{ $pengajuan->konsumen?->nik }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Tanggal Lahir</dt><dd class="text-white">{{ $pengajuan->konsumen?->tanggal_lahir?->format('d M Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Status Perkawinan</dt><dd class="text-white">{{ $pengajuan->konsumen?->status_perkawinan?->label() }}</dd></div>
                @if($pengajuan->konsumen?->nama_pasangan)
                <div class="flex justify-between"><dt class="text-surface-200/50">Nama Pasangan</dt><dd class="text-white">{{ $pengajuan->konsumen->nama_pasangan }}</dd></div>
                @endif
            </dl>
        </div>

        {{-- Kendaraan --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Kendaraan</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Merk</dt><dd class="text-white">{{ $pengajuan->kendaraan?->merk }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Model / Tipe</dt><dd class="text-white">{{ $pengajuan->kendaraan?->model }} {{ $pengajuan->kendaraan?->tipe }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Warna</dt><dd class="text-white">{{ $pengajuan->kendaraan?->warna }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Harga</dt><dd class="text-emerald-400 font-mono font-semibold">Rp {{ number_format($pengajuan->kendaraan?->harga_kendaraan ?? 0, 0, ',', '.') }}</dd></div>
            </dl>
        </div>

        {{-- Pinjaman --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Pinjaman</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Asuransi</dt><dd class="text-white">{{ $pengajuan->pinjaman?->asuransi }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Down Payment</dt><dd class="text-white font-mono">Rp {{ number_format($pengajuan->pinjaman?->down_payment ?? 0, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Tenor</dt><dd class="text-white">{{ $pengajuan->pinjaman?->lama_kredit_bulan }} bulan</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Angsuran/Bulan</dt><dd class="text-emerald-400 font-mono font-semibold">Rp {{ number_format($pengajuan->pinjaman?->angsuran_per_bulan ?? 0, 0, ',', '.') }}</dd></div>
            </dl>
        </div>

        {{-- Dealer --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Dealer</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Nama</dt><dd class="text-white">{{ $pengajuan->dealer->nama_dealer }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Alamat</dt><dd class="text-white">{{ $pengajuan->dealer->alamat }}</dd></div>
            </dl>
        </div>
    </div>

    {{-- Dokumen --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Dokumen Pendukung</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($pengajuan->dokumens->where('is_generated', false) as $dok)
            <div class="flex items-center gap-3 px-4 py-3 bg-surface-800/50 rounded-xl">
                <svg class="w-8 h-8 text-brand-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white">{{ $dok->jenis_dokumen->label() }}</p>
                </div>
                <a href="{{ Storage::url($dok->file_path) }}" target="_blank" class="text-brand-400 hover:text-brand-300 text-xs">Lihat</a>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Action: Approve / Reject --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6" x-data="{ showReject: false }">
        <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Keputusan</h4>
        <div class="flex gap-3 mb-4">
            <form method="POST" action="{{ route('atasan.approval.approve', $pengajuan) }}" onsubmit="return confirm('Setujui pengajuan ini?')">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/25 text-sm">
                    ✓ Setujui
                </button>
            </form>
            <button @click="showReject = !showReject" class="px-6 py-2.5 bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 font-semibold rounded-xl text-sm">
                ✕ Tolak
            </button>
        </div>

        <div x-show="showReject" x-cloak x-transition class="mt-4">
            <form method="POST" action="{{ route('atasan.approval.reject', $pengajuan) }}">
                @csrf
                <label for="catatan_reject" class="block text-sm font-medium text-surface-200/70 mb-1.5">Alasan Penolakan (wajib)</label>
                <textarea id="catatan_reject" name="catatan_reject" required rows="3"
                          class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30 mb-3"
                          placeholder="Jelaskan alasan penolakan...">{{ old('catatan_reject') }}</textarea>
                @error('catatan_reject')
                <p class="text-red-400 text-xs mb-2">{{ $message }}</p>
                @enderror
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-xl text-sm">Konfirmasi Penolakan</button>
            </form>
        </div>
    </div>

    {{-- History --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Riwayat Status</h4>
        <div class="space-y-0">
            @foreach($pengajuan->histories->sortByDesc('created_at') as $history)
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-3 h-3 rounded-full bg-brand-500 ring-4 ring-brand-500/20"></div>
                    @if(!$loop->last)<div class="w-0.5 flex-1 bg-surface-700 my-1"></div>@endif
                </div>
                <div class="pb-6">
                    <p class="text-sm text-white font-medium">{{ $history->status_sebelum ? $history->status_sebelum . ' → ' : '' }}{{ $history->status_sesudah }}</p>
                    @if($history->catatan)<p class="text-xs text-surface-200/60 mt-0.5">{{ $history->catatan }}</p>@endif
                    <p class="text-xs text-surface-200/40 mt-0.5">{{ $history->user->nama_lengkap }} — {{ $history->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
