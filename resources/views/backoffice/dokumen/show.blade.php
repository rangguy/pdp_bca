@extends('layouts.app')

@section('title', 'Generate Dokumen ' . $pengajuan->kode_pengajuan)
@section('heading', 'Generate Dokumen')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-bold text-white font-mono">{{ $pengajuan->kode_pengajuan }}</h3>
            @php $colors = ['emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'blue' => 'bg-blue-500/10 text-blue-400 border-blue-500/20']; @endphp
            <span class="inline-flex mt-1 px-2.5 py-1 rounded-lg text-xs font-medium border {{ $colors[$pengajuan->status->color()] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                {{ $pengajuan->status->label() }}
            </span>
        </div>
        <a href="{{ route('backoffice.dokumen.index') }}" class="text-sm text-surface-200/50 hover:text-white">← Kembali</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Konsumen</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Nama</dt><dd class="text-white">{{ $pengajuan->konsumen?->nama }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">NIK</dt><dd class="text-white font-mono">{{ $pengajuan->konsumen?->nik }}</dd></div>
            </dl>
        </div>
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Kendaraan</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Kendaraan</dt><dd class="text-white">{{ $pengajuan->kendaraan?->merk }} {{ $pengajuan->kendaraan?->model }} {{ $pengajuan->kendaraan?->tipe }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Harga</dt><dd class="text-emerald-400 font-mono">Rp {{ number_format($pengajuan->kendaraan?->harga_kendaraan ?? 0, 0, ',', '.') }}</dd></div>
            </dl>
        </div>
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Pinjaman</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">DP</dt><dd class="text-white font-mono">Rp {{ number_format($pengajuan->pinjaman?->down_payment ?? 0, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Tenor</dt><dd class="text-white">{{ $pengajuan->pinjaman?->lama_kredit_bulan }} bulan</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Angsuran</dt><dd class="text-emerald-400 font-mono">Rp {{ number_format($pengajuan->pinjaman?->angsuran_per_bulan ?? 0, 0, ',', '.') }}</dd></div>
            </dl>
        </div>
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Info</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Marketing</dt><dd class="text-white">{{ $pengajuan->marketing->nama_lengkap }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Dealer</dt><dd class="text-white">{{ $pengajuan->dealer->nama_dealer }}</dd></div>
            </dl>
        </div>
    </div>

    {{-- Generate button or generated docs --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Dokumen Kontrak & PO</h4>

        @if($pengajuan->isGeneratable())
            <form method="POST" action="{{ route('backoffice.dokumen.generate', $pengajuan) }}" onsubmit="return confirm('Generate dokumen kontrak dan PO?')">
                @csrf
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/25 text-sm transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Generate Dokumen Kontrak & PO
                </button>
            </form>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($pengajuan->dokumens->where('is_generated', true) as $dok)
                <div class="flex items-center gap-3 px-4 py-3 bg-emerald-500/5 border border-emerald-500/20 rounded-xl">
                    <svg class="w-8 h-8 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-white">{{ $dok->jenis_dokumen->label() }}</p>
                        <p class="text-xs text-surface-200/50">Digenerate {{ $dok->uploaded_at?->format('d M Y H:i') }}</p>
                    </div>
                    <a href="{{ Storage::url($dok->file_path) }}" target="_blank" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium">Download</a>
                </div>
                @endforeach
            </div>
        @endif
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
