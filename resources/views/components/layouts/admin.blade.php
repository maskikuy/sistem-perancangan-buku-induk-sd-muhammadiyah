<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin' }} - Buku Induk SD Muhammadiyah Sleman</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Desktop & Mobile Offcanvas -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               class="fixed md:sticky top-0 left-0 z-40 w-64 h-screen transition-transform bg-emerald-800 text-white flex flex-col shadow-xl">
            <!-- Sidebar Header -->
            <div class="p-5 flex items-center justify-between border-b border-emerald-700/60">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-700 flex items-center justify-center font-bold text-lg text-emerald-200 shadow">
                        SD
                    </div>
                    <div>
                        <h1 class="text-sm font-bold leading-tight">SD MUHAMMADIYAH</h1>
                        <p class="text-[11px] text-emerald-300">Buku Induk Terintegrasi</p>
                    </div>
                </div>
                <!-- Close Mobile Sidebar -->
                <button @click="sidebarOpen = false" class="md:hidden text-emerald-200 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <div class="pt-3 pb-1 px-3 text-[11px] font-semibold text-emerald-400 uppercase tracking-wider">
                    Buku Induk & Siswa
                </div>

                <a href="{{ route('admin.siswa.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.siswa.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Buku Induk Siswa
                </a>

                <a href="{{ route('admin.mutasi.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.mutasi.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Mutasi Siswa
                </a>

                <a href="{{ route('admin.kenaikan.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.kenaikan.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Kenaikan & Kelulusan
                </a>

                <div class="pt-3 pb-1 px-3 text-[11px] font-semibold text-emerald-400 uppercase tracking-wider">
                    Data Master Akademik
                </div>

                <a href="{{ route('admin.tahun-ajaran.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.tahun-ajaran.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tahun Ajaran & Semester
                </a>

                <a href="{{ route('admin.kelas.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.kelas.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Rombel Kelas
                </a>

                <a href="{{ route('admin.mapel.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.mapel.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Mata Pelajaran
                </a>

                <a href="{{ route('admin.guru.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.guru.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Data Guru
                </a>

                <a href="{{ route('admin.jadwal.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.jadwal.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Jadwal Pelajaran
                </a>

                <div class="pt-3 pb-1 px-3 text-[11px] font-semibold text-emerald-400 uppercase tracking-wider">
                    Layanan & Sistem
                </div>

                <a href="{{ route('admin.akun-wali.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.akun-wali.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Aktivasi & Akun Wali
                </a>

                <a href="{{ route('admin.pengumuman.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.pengumuman.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    Pengumuman Sekolah
                </a>

                <a href="{{ route('admin.audit-logs.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.audit-logs.*') ? 'bg-emerald-900 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-700/50' }}">
                    <svg class="w-5 h-5 mr-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Audit Trail Log
                </a>
            </div>

            <!-- Sidebar Footer User Profile -->
            <div class="p-3 border-t border-emerald-700/60 bg-emerald-900/40">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center font-bold text-xs text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="truncate max-w-[120px]">
                            <p class="text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-emerald-300 capitalize">{{ auth()->user()->role_aktif }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar" class="p-1.5 text-emerald-300 hover:text-white rounded hover:bg-emerald-700/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
            <!-- Topbar Header -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
                <div class="px-4 sm:px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <h2 class="text-base sm:text-lg font-bold text-gray-800">{{ $header ?? 'Panel Administrasi' }}</h2>
                    </div>

                    <div class="flex items-center space-x-3">
                        <!-- Indikator Tahun Ajaran Aktif -->
                        <div class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            TA Aktif: 2026/2027 (Ganjil)
                        </div>

                        <!-- Real-time Clock & Date -->
                        <x-realtime-clock variant="badge" />
                    </div>
                </div>
            </header>

            <!-- Flash Alert Messages -->
            <div class="px-4 sm:px-6 pt-4">
                @if(session('success'))
                    <div class="p-3 mb-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm rounded shadow-sm flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-3 mb-3 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-sm rounded shadow-sm flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-rose-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Content Slot -->
            <main class="flex-1 px-4 sm:px-6 pb-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-3 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} SD Muhammadiyah Sleman &bull; Sistem Informasi Buku Induk Terintegrasi
            </footer>
        </div>
    </div>
</body>
</html>
