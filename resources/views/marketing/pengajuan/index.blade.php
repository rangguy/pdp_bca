@extends('layouts.app')

@section('title', 'Pengajuan Saya')
@section('heading', 'Pengajuan Saya')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-surface-200/50">Kelola semua pengajuan kredit Anda</p>
        </div>
        <a href="{{ route('marketing.pengajuan.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 text-sm transform hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Pengajuan Baru
        </a>
    </div>

    {{-- Filter --}}
    <div class="flex gap-2 flex-wrap" x-data>
        <a href="{{ route('marketing.pengajuan.index') }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium {{ !request('status') ? 'bg-brand-600/20 text-brand-400 border border-brand-500/30' : 'bg-surface-800 text-surface-200/60 border border-surface-700 hover:text-white' }}">
            Semua
        </a>
        @foreach(\App\Enums\StatusPengajuan::cases() as $status)
        <a href="{{ route('marketing.pengajuan.index', ['status' => $status->value]) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium {{ request('status') === $status->value ? 'bg-brand-600/20 text-brand-400 border border-brand-500/30' : 'bg-surface-800 text-surface-200/60 border border-surface-700 hover:text-white' }}">
            {{ $status->label() }}
        </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-surface-900 border border-surface-800 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-800 text-left">
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Konsumen</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Dealer</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-800/50">
                    @forelse($pengajuans as $p)
                    <tr class="hover:bg-surface-800/30">
                        <td class="px-6 py-4 font-mono text-xs text-brand-400">{{ $p->kode_pengajuan }}</td>
                        <td class="px-6 py-4 text-white">{{ $p->konsumen?->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-surface-200/70">{{ $p->dealer->nama_dealer }}</td>
                        <td class="px-6 py-4">
                            @php $colors = ['gray' => 'bg-gray-500/10 text-gray-400 border-gray-500/20', 'amber' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'red' => 'bg-red-500/10 text-red-400 border-red-500/20', 'blue' => 'bg-blue-500/10 text-blue-400 border-blue-500/20']; @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-medium border {{ $colors[$p->status->color()] ?? $colors['gray'] }}">
                                {{ $p->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-surface-200/50 text-xs">{{ $p->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('marketing.pengajuan.show', $p) }}" class="p-1.5 rounded-lg text-surface-200/50 hover:text-white hover:bg-surface-800" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($p->isEditable())
                                <a href="{{ route('marketing.pengajuan.edit', $p) }}" class="p-1.5 rounded-lg text-surface-200/50 hover:text-brand-400 hover:bg-surface-800" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-surface-200/40">
                            <svg class="w-12 h-12 mx-auto mb-3 text-surface-200/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p>Belum ada pengajuan.</p>
                            <a href="{{ route('marketing.pengajuan.create') }}" class="text-brand-400 hover:text-brand-300 text-sm mt-1 inline-block">Buat pengajuan pertama →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengajuans->hasPages())
        <div class="px-6 py-4 border-t border-surface-800">
            {{ $pengajuans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
