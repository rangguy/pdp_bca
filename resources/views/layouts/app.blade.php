<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PDP BCA') — Pengajuan Kredit Digital</title>
    <meta name="description" content="Sistem Digitalisasi Proses Pengajuan Kredit PT. JKL">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-950 text-surface-100 min-h-screen font-sans antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-30 w-64 transform bg-surface-900 border-r border-surface-800 transition-transform duration-300"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               x-cloak>
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-surface-800">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white tracking-tight">PDP BCA</h1>
                    <p class="text-xs text-surface-200/60">Pengajuan Kredit</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="mt-4 px-3 space-y-1">
                @auth
                    @if(auth()->user()->isMarketing())
                        <a href="{{ route('marketing.pengajuan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('marketing.pengajuan.*') ? 'bg-brand-600/20 text-brand-400' : 'text-surface-200/70 hover:bg-surface-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Pengajuan Saya
                        </a>
                    @endif

                    @if(auth()->user()->isAtasanMarketing())
                        <a href="{{ route('atasan.approval.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('atasan.approval.*') ? 'bg-brand-600/20 text-brand-400' : 'text-surface-200/70 hover:bg-surface-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Antrian Approval
                        </a>
                    @endif

                    @if(auth()->user()->isAdminBackoffice())
                        <a href="{{ route('backoffice.dokumen.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('backoffice.dokumen.*') ? 'bg-brand-600/20 text-brand-400' : 'text-surface-200/70 hover:bg-surface-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Generate Dokumen
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.*') ? 'bg-brand-600/20 text-brand-400' : 'text-surface-200/70 hover:bg-surface-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Dashboard
                        </a>
                    @endif
                @endauth
            </nav>

            {{-- User card at bottom --}}
            @auth
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-surface-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand-600 flex items-center justify-center text-xs font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->nama_lengkap }}</p>
                        <p class="text-xs text-surface-200/50 truncate">{{ auth()->user()->role->label() }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg text-surface-200/50 hover:text-red-400 hover:bg-surface-800" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </aside>

        {{-- Main content --}}
        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">
            {{-- Top bar --}}
            <header class="sticky top-0 z-20 bg-surface-900/80 backdrop-blur-xl border-b border-surface-800">
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-surface-200/60 hover:text-white hover:bg-surface-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <h2 class="text-lg font-semibold text-white">@yield('heading', 'Dashboard')</h2>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- Notification bell --}}
                        @auth
                        <div class="relative" x-data>
                            <button @click="$store.notifikasi.toggle()" class="relative p-2 rounded-lg text-surface-200/60 hover:text-white hover:bg-surface-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span x-show="$store.notifikasi.unreadCount > 0"
                                      x-text="$store.notifikasi.unreadCount"
                                      x-cloak
                                      class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse"></span>
                            </button>

                            {{-- Notification dropdown --}}
                            <div x-show="$store.notifikasi.isOpen"
                                 @click.outside="$store.notifikasi.isOpen = false"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute right-0 mt-2 w-80 bg-surface-850 border border-surface-700 rounded-xl shadow-2xl overflow-hidden">
                                <div class="flex items-center justify-between px-4 py-3 border-b border-surface-700">
                                    <h3 class="text-sm font-semibold text-white">Notifikasi</h3>
                                    <button @click="$store.notifikasi.markAllAsRead()" class="text-xs text-brand-400 hover:text-brand-300">Tandai semua dibaca</button>
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    <template x-for="notif in $store.notifikasi.items" :key="notif.id">
                                        <a :href="notif.link || '#'" @click="$store.notifikasi.markAsRead(notif.id)"
                                           class="block px-4 py-3 border-b border-surface-800/50 hover:bg-surface-800/50"
                                           :class="!notif.is_read ? 'bg-brand-950/20' : ''">
                                            <p class="text-sm font-medium text-white" x-text="notif.judul"></p>
                                            <p class="text-xs text-surface-200/60 mt-0.5" x-text="notif.pesan"></p>
                                        </a>
                                    </template>
                                    <div x-show="$store.notifikasi.items.length === 0" class="px-4 py-8 text-center text-surface-200/40 text-sm">
                                        Tidak ada notifikasi
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- Flash messages --}}
            <div class="px-6 pt-4">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         x-transition class="mb-4 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
                         x-transition class="mb-4 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- Page content --}}
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
