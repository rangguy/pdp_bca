@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('heading', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $totalPengajuan = \App\Models\PengajuanKredit::count();
            $menungguApproval = \App\Models\PengajuanKredit::where('status', 'MENUNGGU_APPROVAL')->count();
            $disetujui = \App\Models\PengajuanKredit::where('status', 'DISETUJUI')->count();
            $dokumenSiap = \App\Models\PengajuanKredit::where('status', 'DOKUMEN_SIAP')->count();
        @endphp

        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <p class="text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Total Pengajuan</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $totalPengajuan }}</p>
        </div>
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <p class="text-xs font-semibold text-amber-400/70 uppercase tracking-wider">Menunggu Approval</p>
            <p class="text-3xl font-bold text-amber-400 mt-2">{{ $menungguApproval }}</p>
        </div>
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <p class="text-xs font-semibold text-emerald-400/70 uppercase tracking-wider">Disetujui</p>
            <p class="text-3xl font-bold text-emerald-400 mt-2">{{ $disetujui }}</p>
        </div>
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
            <p class="text-xs font-semibold text-blue-400/70 uppercase tracking-wider">Dokumen Siap</p>
            <p class="text-3xl font-bold text-blue-400 mt-2">{{ $dokumenSiap }}</p>
        </div>
    </div>

    <div class="bg-surface-900 border border-surface-800 rounded-2xl p-6">
        <h4 class="text-sm font-semibold text-surface-200/50 uppercase tracking-wider mb-4">Pengajuan Terbaru</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-800 text-left">
                        <th class="px-4 py-2 text-xs font-semibold text-surface-200/50">Kode</th>
                        <th class="px-4 py-2 text-xs font-semibold text-surface-200/50">Marketing</th>
                        <th class="px-4 py-2 text-xs font-semibold text-surface-200/50">Status</th>
                        <th class="px-4 py-2 text-xs font-semibold text-surface-200/50">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-800/50">
                    @foreach(\App\Models\PengajuanKredit::with('marketing')->orderByDesc('created_at')->limit(10)->get() as $p)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs text-brand-400">{{ $p->kode_pengajuan }}</td>
                        <td class="px-4 py-3 text-white">{{ $p->marketing->nama_lengkap }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium">{{ $p->status->label() }}</span>
                        </td>
                        <td class="px-4 py-3 text-surface-200/50 text-xs">{{ $p->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
