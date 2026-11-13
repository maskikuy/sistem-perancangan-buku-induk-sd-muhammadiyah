<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Ringkasan Dashboard Sekolah</x-slot>

    <!-- Kartu Statistik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Siswa Aktif -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa Aktif</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalSiswa }}</h3>
                <p class="text-[11px] text-emerald-600 mt-1 font-medium">Terdaftar di Buku Induk</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>

        <!-- Tenaga Pendidik / Guru -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Guru</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalGuru }}</h3>
                <p class="text-[11px] text-teal-600 mt-1 font-medium">Wali Kelas & Guru Mapel</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
        </div>

        <!-- Rombongan Belajar (Kelas) -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rombel Kelas</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalKelas }}</h3>
                <p class="text-[11px] text-blue-600 mt-1 font-medium">Tingkat 1 s/d 6</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <!-- Tahun Ajaran Aktif -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun Ajaran</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $taAktif?->nama ?? '-' }}</h3>
                <p class="text-[11px] text-amber-600 mt-1 font-medium">Semester Ganjil Aktif</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Baris Tengah: Rekap Presensi & Pengajuan Menunggu -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Rekap Presensi Hari Ini -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                <h4 class="font-bold text-sm text-gray-800 flex items-center">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full mr-2"></span>
                    Presensi Siswa Hari Ini
                </h4>
                <span class="text-xs text-gray-400 font-mono">{{ date('d M Y') }}</span>
            </div>

            <div class="grid grid-cols-5 gap-2 text-center">
                <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-100">
                    <span class="text-lg font-bold text-emerald-700">{{ $rekapPresensi['hadir'] }}</span>
                    <span class="block text-[10px] text-emerald-600 font-medium">Hadir</span>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg border border-blue-100">
                    <span class="text-lg font-bold text-blue-700">{{ $rekapPresensi['izin'] }}</span>
                    <span class="block text-[10px] text-blue-600 font-medium">Izin</span>
                </div>
                <div class="p-2 bg-amber-50 rounded-lg border border-amber-100">
                    <span class="text-lg font-bold text-amber-700">{{ $rekapPresensi['sakit'] }}</span>
                    <span class="block text-[10px] text-amber-600 font-medium">Sakit</span>
                </div>
                <div class="p-2 bg-rose-50 rounded-lg border border-rose-100">
                    <span class="text-lg font-bold text-rose-700">{{ $rekapPresensi['alpa'] }}</span>
                    <span class="block text-[10px] text-rose-600 font-medium">Alpa</span>
                </div>
                <div class="p-2 bg-purple-50 rounded-lg border border-purple-100">
                    <span class="text-lg font-bold text-purple-700">{{ $rekapPresensi['pulang'] }}</span>
                    <span class="block text-[10px] text-purple-600 font-medium">Pulang</span>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                <span class="text-gray-500">Total Terdata: {{ array_sum($rekapPresensi) }} siswa</span>
                <a href="{{ route('admin.laporan.presensi') }}" class="text-emerald-600 hover:underline font-medium">Cetak Rekap &rarr;</a>
            </div>
        </div>

        <!-- Pengajuan Izin / Penjemputan Menunggu -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                <h4 class="font-bold text-sm text-gray-800 flex items-center">
                    <span class="w-2.5 h-2.5 bg-amber-500 rounded-full mr-2"></span>
                    Pengajuan Izin & Penjemputan Terbaru
                </h4>
                <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full font-medium">
                    {{ $pengajuanMenunggu->count() }} Menunggu
                </span>
            </div>

            @if($pengajuanMenunggu->isEmpty())
                <p class="text-xs text-gray-400 py-6 text-center italic">Tidak ada pengajuan yang sedang menunggu persetujuan saat ini.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($pengajuanMenunggu as $aju)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="px-2 py-0.5 rounded font-medium {{ $aju->jenis === 'penjemputan' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $aju->jenis)) }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $aju->siswa->nama_lengkap }}</p>
                                    <p class="text-[11px] text-gray-500">Wali: {{ $aju->wali->nama_lengkap }} &bull; {{ $aju->alasan }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-gray-400 text-[10px]">{{ $aju->created_at->diffForHumans() }}</span>
                                <span class="block px-2 py-0.5 mt-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800">Menunggu</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Baris Bawah: Pengumuman Sekolah & Audit Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pengumuman Terbit -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                <h4 class="font-bold text-sm text-gray-800">Pengumuman Sekolah</h4>
                <a href="{{ route('admin.pengumuman.index') }}" class="text-xs text-emerald-600 hover:underline">Kelola &rarr;</a>
            </div>

            <div class="space-y-3">
                @foreach($pengumuman as $p)
                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] px-2 py-0.5 rounded font-medium bg-emerald-100 text-emerald-800 uppercase">
                                Sasaran: {{ $p->sasaran }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $p->tanggal_terbit->format('d/m/Y') }}</span>
                        </div>
                        <h5 class="text-xs font-bold text-gray-800">{{ $p->judul }}</h5>
                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $p->isi }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Audit Trail Log Aktivitas -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                <h4 class="font-bold text-sm text-gray-800">Aktivitas Audit Trail Terbaru</h4>
                <a href="{{ route('admin.audit-logs.index') }}" class="text-xs text-emerald-600 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($auditLogsTerbaru as $log)
                    <div class="py-2 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2">
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold {{ $log->aksi === 'LOGIN' ? 'bg-blue-100 text-blue-700' : ($log->aksi === 'CREATE' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $log->aksi }}
                            </span>
                            <div>
                                <span class="font-medium text-gray-700">{{ $log->user?->name ?? 'Sistem' }}</span>
                                <span class="text-gray-400 text-[11px]">pada tabel {{ $log->tabel_terkait }}</span>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-dynamic-component>
