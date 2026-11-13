<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal Guru' }} - SD Muhammadiyah Sleman</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Guru -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               class="fixed md:sticky top-0 left-0 z-40 w-64 h-screen transition-transform bg-teal-800 text-white flex flex-col shadow-xl">
            <!-- Sidebar Header -->
            <div class="p-5 flex items-center justify-between border-b border-teal-700/60">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-teal-700 flex items-center justify-center font-bold text-lg text-teal-200 shadow">
                        GU
                    </div>
                    <div>
                        <h1 class="text-sm font-bold leading-tight">PORTAL GURU</h1>
                        <p class="text-[11px] text-teal-300">SD Muhammadiyah Sleman</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-teal-200 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Nav Links -->
            <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('guru.dashboard') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('guru.dashboard') ? 'bg-teal-900 text-white font-semibold' : 'text-teal-100 hover:bg-teal-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <div class="pt-3 pb-1 px-3 text-[11px] font-semibold text-teal-300 uppercase tracking-wider">
                    Aktivitas Kelas
                </div>

                <a href="{{ route('guru.presensi.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('guru.presensi.*') ? 'bg-teal-900 text-white font-semibold' : 'text-teal-100 hover:bg-teal-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Presensi Harian Siswa
                </a>

                <a href="{{ route('guru.nilai.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('guru.nilai.*') ? 'bg-teal-900 text-white font-semibold' : 'text-teal-100 hover:bg-teal-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Input Nilai Akademik
                </a>

                <a href="{{ route('guru.catatan.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('guru.catatan.*') ? 'bg-teal-900 text-white font-semibold' : 'text-teal-100 hover:bg-teal-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    Catatan Rapor Wali Kelas
                </a>

                <a href="{{ route('guru.jadwal.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('guru.jadwal.*') ? 'bg-teal-900 text-white font-semibold' : 'text-teal-100 hover:bg-teal-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal Mengajar Pribadi
                </a>

                <div class="pt-3 pb-1 px-3 text-[11px] font-semibold text-teal-300 uppercase tracking-wider">
                    Layanan & Persetujuan
                </div>

                <a href="{{ route('guru.pengajuan.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('guru.pengajuan.*') ? 'bg-teal-900 text-white font-semibold' : 'text-teal-100 hover:bg-teal-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Persetujuan Izin & Jemput
                </a>

                <!-- Akses Cepat Petugas Piket (Gerbang) -->
                <a href="{{ route('piket.penjemputan') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('piket.*') ? 'bg-amber-600 text-white font-semibold' : 'text-amber-200 hover:bg-teal-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Piket Gerbang (Verifikasi)
                </a>
            </div>

            <!-- Footer User Profile -->
            <div class="p-3 border-t border-teal-700/60 bg-teal-900/40">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center font-bold text-xs text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="truncate max-w-[120px]">
                            <p class="text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-teal-300">Guru / Wali Kelas</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar" class="p-1.5 text-teal-300 hover:text-white rounded hover:bg-teal-700/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
            <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
                <div class="px-4 sm:px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <h2 class="text-base sm:text-lg font-bold text-gray-800">{{ $header ?? 'Panel Guru & Wali Kelas' }}</h2>
                    </div>

                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-gray-500 font-mono">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </header>

            <div class="px-4 sm:px-6 pt-4">
                @if(session('success'))
                    <div class="p-3 mb-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm rounded shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-3 mb-3 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-sm rounded shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <main class="flex-1 px-4 sm:px-6 pb-8">
                {{ $slot }}
            </main>

            <footer class="bg-white border-t border-gray-200 px-6 py-3 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} SD Muhammadiyah Sleman &bull; Portal Guru & Wali Kelas
            </footer>
        </div>
    </div>
</body>
</html>
