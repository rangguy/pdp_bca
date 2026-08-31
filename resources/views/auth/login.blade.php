<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — PDP BCA</title>
    <meta name="description" content="Login ke Sistem Pengajuan Kredit Digital PT. JKL">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-950 text-surface-100 min-h-screen font-sans antialiased flex items-center justify-center">
    <div class="w-full max-w-md mx-auto px-6">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 mb-4 shadow-lg shadow-brand-500/20">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-white">PDP BCA</h1>
            <p class="text-sm text-surface-200/50 mt-1">Sistem Pengajuan Kredit Digital</p>
        </div>

        {{-- Login card --}}
        <div class="bg-surface-900 border border-surface-800 rounded-2xl p-8 shadow-xl">
            <h2 class="text-lg font-semibold text-white mb-6">Masuk ke akun Anda</h2>

            @if($errors->any())
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-medium text-surface-200/70 mb-1.5">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                           class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30 focus:border-brand-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-surface-200/70 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-white placeholder-surface-200/30 focus:border-brand-500">
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded bg-surface-800 border-surface-700 text-brand-600 focus:ring-brand-500">
                    <label for="remember" class="ml-2 text-sm text-surface-200/60">Ingat saya</label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 transform hover:-translate-y-0.5 active:translate-y-0">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-surface-200/30 mt-6">PT. JKL — Sistem Internal</p>
    </div>
</body>
</html>
