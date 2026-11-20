<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#059669">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Buku Induk">

    <title>{{ $title ?? 'Portal Wali Murid' }} - SD Muhammadiyah Sleman</title>

    <!-- Manifest PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800 pb-20 select-none">
    <!-- PWA Topbar (App Header) -->
    <header class="bg-emerald-700 text-white shadow-md sticky top-0 z-30">
        <div class="max-w-lg mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-800/80 flex items-center justify-center font-bold text-sm text-emerald-200">
                    SD
                </div>
                <div>
                    <h1 class="text-xs font-bold leading-tight uppercase tracking-wider">SD MUHAMMADIYAH SLEMAN</h1>
                    <p class="text-[10px] text-emerald-200">Portal Wali Murid PWA</p>
                </div>
            </div>

            <!-- Profile & Logout Dropdown -->
            <div class="flex items-center space-x-2" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-1.5 text-xs text-emerald-100 hover:text-white focus:outline-none">
                    <span class="max-w-[100px] truncate font-medium">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak
                     class="absolute right-4 top-12 w-48 bg-white rounded-lg shadow-xl py-1 text-gray-800 border border-gray-100 z-50 text-xs">
                    <div class="px-3 py-2 border-b border-gray-100">
                        <p class="font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-500">{{ auth()->user()->username }}</p>
                    </div>
                    <a href="{{ route('wali.anak') }}" class="block px-3 py-2 hover:bg-gray-50">Profil Anak</a>
                    <a href="{{ route('wali.penjemput.index') }}" class="block px-3 py-2 hover:bg-gray-50">Penjemput Terpercaya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-rose-600 hover:bg-rose-50 border-t border-gray-100">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @php
            $wali = auth()->user()->wali;
            $daftarAnak = $wali ? $wali->siswa : collect();
            $anakAktifId = session('anak_aktif_id', $daftarAnak->first()?->id);
            $anakAktif = $daftarAnak->firstWhere('id', $anakAktifId) ?? $daftarAnak->first();
        @endphp

        <!-- CHILD SWITCHER (Pemilih Banyak Anak Dalam 1 Akun) -->
        @if($daftarAnak->count() > 1)
            <div class="bg-emerald-800/90 border-t border-emerald-600/50 py-1.5 px-4">
                <div class="max-w-lg mx-auto flex items-center justify-between">
                    <span class="text-[11px] text-emerald-200 font-medium flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Pilih Anak:
                    </span>
                    <form method="POST" action="{{ route('wali.pilih-anak') }}" id="formPilihAnak">
                        @csrf
                        <select name="siswa_id" 
                                onchange="document.getElementById('formPilihAnak').submit()"
                                class="text-xs bg-emerald-900 text-white rounded-md border-emerald-600 py-1 px-2.5 focus:ring-emerald-400 focus:border-emerald-400 font-medium shadow-sm">
                            @foreach($daftarAnak as $anak)
                                <option value="{{ $anak->id }}" {{ $anak->id == $anakAktifId ? 'selected' : '' }}>
                                    {{ $anak->nama_panggilan ?? $anak->nama_lengkap }} (Kelas {{ $anak->kelasAktif?->kelas?->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        @elseif($anakAktif)
            <!-- Info Siswa Tunggal -->
            <div class="bg-emerald-800/90 border-t border-emerald-600/50 py-1 px-4 text-center">
                <p class="text-[11px] text-emerald-100 font-medium">
                    Siswa: <span class="font-bold text-white">{{ $anakAktif->nama_lengkap }}</span> ({{ $anakAktif->kelasAktif?->kelas?->nama_kelas ?? 'Kelas 1' }})
                </p>
            </div>
        @endif
    </header>

    <!-- Main Container -->
    <main class="max-w-lg mx-auto px-4 py-3">
        <!-- Live Date & Realtime Clock Indicator -->
        <div class="flex items-center justify-between mb-3 px-1 text-xs">
            <span class="font-bold text-gray-700 text-xs sm:text-sm">{{ $header ?? 'Beranda Wali' }}</span>
            <x-realtime-clock variant="minimal" />
        </div>
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="p-3 mb-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl shadow-sm flex items-center">
                <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 mb-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-xl shadow-sm flex items-center">
                <svg class="w-4 h-4 text-rose-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Bottom Navigation Bar (Mobile-First) -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 max-w-lg mx-auto shadow-2xl">
        <div class="grid grid-cols-5 text-center py-2">
            <!-- Beranda -->
            <a href="{{ route('wali.dashboard') }}" 
               class="flex flex-col items-center justify-center text-xs font-medium transition {{ request()->routeIs('wali.dashboard') ? 'text-emerald-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Beranda</span>
            </a>

            <!-- Data Anak -->
            <a href="{{ route('wali.anak') }}" 
               class="flex flex-col items-center justify-center text-xs font-medium transition {{ request()->routeIs('wali.anak*') ? 'text-emerald-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>Anak</span>
            </a>

            <!-- Presensi -->
            <a href="{{ route('wali.presensi') }}" 
               class="flex flex-col items-center justify-center text-xs font-medium transition {{ request()->routeIs('wali.presensi*') ? 'text-emerald-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Presensi</span>
            </a>

            <!-- Nilai Rapor -->
            <a href="{{ route('wali.nilai') }}" 
               class="flex flex-col items-center justify-center text-xs font-medium transition {{ request()->routeIs('wali.nilai*') ? 'text-emerald-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Rapor</span>
            </a>

            <!-- Layanan / Pengajuan -->
            <a href="{{ route('wali.pengajuan.index') }}" 
               class="flex flex-col items-center justify-center text-xs font-medium transition {{ request()->routeIs('wali.pengajuan*') ? 'text-emerald-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Layanan</span>
            </a>
        </div>
    </nav>

    <!-- Service Worker Registration for PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW Registered!', reg.scope))
                    .catch(err => console.log('SW Registration failed:', err));
            });
        }
    </script>
</body>
</html>
