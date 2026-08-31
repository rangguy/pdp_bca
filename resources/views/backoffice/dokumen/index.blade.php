@extends('layouts.app')

@section('title', 'Generate Dokumen')
@section('heading', 'Generate Dokumen')

@section('content')
<div class="space-y-6">
    <p class="text-sm text-surface-200/50">Pengajuan yang disetujui dan siap di-generate dokumennya</p>

    <div class="bg-surface-900 border border-surface-800 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-800 text-left">
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Marketing</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Konsumen</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Dealer</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-surface-200/50 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-800/50">
                    @forelse($pengajuans as $p)
                    <tr class="hover:bg-surface-800/30">
                        <td class="px-6 py-4 font-mono text-xs text-brand-400">{{ $p->kode_pengajuan }}</td>
                        <td class="px-6 py-4 text-white">{{ $p->marketing->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-white">{{ $p->konsumen?->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-surface-200/70">{{ $p->dealer->nama_dealer }}</td>
                        <td class="px-6 py-4">
                            @php $colors = ['emerald' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'blue' => 'bg-blue-500/10 text-blue-400 border-blue-500/20']; @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-medium border {{ $colors[$p->status->color()] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                                {{ $p->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('backoffice.dokumen.show', $p) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-brand-600/20 text-brand-400 border border-brand-500/30 hover:bg-brand-600/30">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-surface-200/40">
                            <p>Belum ada pengajuan yang siap untuk generate dokumen.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengajuans->hasPages())
        <div class="px-6 py-4 border-t border-surface-800">{{ $pengajuans->links() }}</div>
        @endif
    </div>
</div>
@endsection
