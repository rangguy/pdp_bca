@extends('layouts.app')

@section('title', 'Detail Pengajuan ' . $pengajuan->kode_pengajuan)
@section('heading', 'Detail Pengajuan')

@section('content')
<div class="max-w-4xl space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-white font-mono">{{ $pengajuan->kode_pengajuan }}</h3>
            @php $colors = ['gray' => 'bg-gray-500/10 text-gray-400 border-gray-500/20', 'amber' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'red' => 'bg-red-500/10 text-red-400 border-red-500/20', 'blue' => 'bg-blue-500/10 text-blue-400 border-blue-500/20']; @endphp
            <span class="inline-flex mt-1 px-2.5 py-1 rounded-lg text-xs font-medium border {{ $colors[$pengajuan->status->color()] ?? $colors['gray'] }}">
                {{ $pengajuan->status->label() }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            @if($pengajuan->isEditable())
                <a href="{{ route('marketing.pengajuan.edit', $pengajuan) }}" class="px-4 py-2 rounded-xl text-sm font-medium bg-surface-800 border border-surface-700 text-surface-200/70 hover:text-white">
                    Edit
                </a>
            @endif
            @if($pengajuan->isSubmittable())
                <form method="POST" action="{{ route('marketing.pengajuan.submit', $pengajuan) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-500 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/25 text-sm">
                        Submit untuk Approval
                    </button>
                </form>
            @endif
            <a href="{{ route('marketing.pengajuan.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-surface-200/50 hover:text-white">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Catatan reject --}}
    @if($pengajuan->status === \App\Enums\StatusPengajuan::Ditolak && $pengajuan->catatan_reject)
    <div class="px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        <p class="font-semibold mb-1">Alasan Penolakan:</p>
        <p>{{ $pengajuan->catatan_reject }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Konsumen --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Konsumen</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Nama</dt><dd class="text-white font-medium">{{ $pengajuan->konsumen?->nama ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">NIK</dt><dd class="text-white font-mono">{{ $pengajuan->konsumen?->nik ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Tanggal Lahir</dt><dd class="text-white">{{ $pengajuan->konsumen?->tanggal_lahir?->format('d M Y') ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Status Perkawinan</dt><dd class="text-white">{{ $pengajuan->konsumen?->status_perkawinan?->label() ?? '-' }}</dd></div>
                @if($pengajuan->konsumen?->nama_pasangan)
                <div class="flex justify-between"><dt class="text-surface-200/50">Nama Pasangan</dt><dd class="text-white">{{ $pengajuan->konsumen->nama_pasangan }}</dd></div>
                @endif
            </dl>
        </div>

        {{-- Kendaraan --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Kendaraan</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Merk</dt><dd class="text-white font-medium">{{ $pengajuan->kendaraan?->merk ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Model</dt><dd class="text-white">{{ $pengajuan->kendaraan?->model ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Tipe</dt><dd class="text-white">{{ $pengajuan->kendaraan?->tipe ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Warna</dt><dd class="text-white">{{ $pengajuan->kendaraan?->warna ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Harga</dt><dd class="text-emerald-400 font-mono font-semibold">Rp {{ number_format($pengajuan->kendaraan?->harga_kendaraan ?? 0, 0, ',', '.') }}</dd></div>
            </dl>
        </div>

        {{-- Pinjaman --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Data Pinjaman</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Asuransi</dt><dd class="text-white">{{ $pengajuan->pinjaman?->asuransi ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Down Payment</dt><dd class="text-white font-mono">Rp {{ number_format($pengajuan->pinjaman?->down_payment ?? 0, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Tenor</dt><dd class="text-white">{{ $pengajuan->pinjaman?->lama_kredit_bulan ?? '-' }} bulan</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Angsuran/Bulan</dt><dd class="text-emerald-400 font-mono font-semibold">Rp {{ number_format($pengajuan->pinjaman?->angsuran_per_bulan ?? 0, 0, ',', '.') }}</dd></div>
            </dl>
        </div>

        {{-- Dealer & Info --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Info Lainnya</h4>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-surface-200/50">Dealer</dt><dd class="text-white">{{ $pengajuan->dealer->nama_dealer }}</dd></div>
                <div class="flex justify-between"><dt class="text-surface-200/50">Dibuat</dt><dd class="text-white">{{ $pengajuan->created_at->format('d M Y H:i') }}</dd></div>
                @if($pengajuan->tanggal_submit)
                <div class="flex justify-between"><dt class="text-surface-200/50">Disubmit</dt><dd class="text-white">{{ $pengajuan->tanggal_submit->format('d M Y H:i') }}</dd></div>
                @endif
                @if($pengajuan->tanggal_approval)
                <div class="flex justify-between"><dt class="text-surface-200/50">Tanggal Approval</dt><dd class="text-white">{{ $pengajuan->tanggal_approval->format('d M Y H:i') }}</dd></div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Dokumen --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Dokumen</h4>
        @if($pengajuan->dokumens->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($pengajuan->dokumens as $dok)
            <div class="flex items-center gap-3 px-4 py-3 bg-surface-800/50 rounded-xl">
                <svg class="w-8 h-8 text-brand-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white">{{ $dok->jenis_dokumen->label() }}</p>
                    <p class="text-xs text-surface-200/50">{{ $dok->is_generated ? 'Digenerate sistem' : 'Diupload' }} — {{ $dok->uploaded_at?->format('d M Y H:i') }}</p>
                </div>
                <a href="{{ Storage::url($dok->file_path) }}" target="_blank" class="text-brand-400 hover:text-brand-300 text-xs">Lihat</a>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-surface-200/40">Belum ada dokumen.</p>
        @endif
    </div>

    {{-- Timeline / History --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Riwayat Status</h4>
        <div class="space-y-0">
            @foreach($pengajuan->histories->sortByDesc('created_at') as $history)
            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-3 h-3 rounded-full bg-brand-500 ring-4 ring-brand-500/20"></div>
                    @if(!$loop->last)
                    <div class="w-0.5 flex-1 bg-surface-700 my-1"></div>
                    @endif
                </div>
                <div class="pb-6">
                    <p class="text-sm text-white font-medium">
                        {{ $history->status_sebelum ? $history->status_sebelum . ' → ' : '' }}{{ $history->status_sesudah }}
                    </p>
                    @if($history->catatan)
                    <p class="text-xs text-surface-200/60 mt-0.5">{{ $history->catatan }}</p>
                    @endif
                    <p class="text-xs text-surface-200/40 mt-0.5">{{ $history->user->nama_lengkap }} — {{ $history->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
