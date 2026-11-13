<x-dynamic-component :component="'layouts.guru'">
    <x-slot name="header">Selamat Datang, {{ $guru?->nama_gelar ?? auth()->user()->name }}</x-slot>

    <!-- Kartu Informasi Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Kelas Perwalian -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas Binaan (Wali Kelas)</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">
                    {{ $kelasWali ? $kelasWali->nama_kelas : 'Bukan Wali Kelas' }}
                </h3>
                <p class="text-[11px] text-teal-600 mt-1 font-medium">
                    {{ $totalSiswaWali }} Siswa Terdaftar
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <!-- Pengajuan Menunggu Verifikasi -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Perlu Persetujuan</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $pengajuanMenunggu->count() }}</h3>
                <p class="text-[11px] text-gray-500 mt-1">Izin & Penjemputan</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Jadwal Mengajar Hari Ini -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between sm:col-span-2 lg:col-span-1">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal Hari Ini</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $jadwalHariIni->count() }} Jam Pelajaran</h3>
                <p class="text-[11px] text-teal-600 mt-1 font-medium capitalize">{{ Carbon\Carbon::now()->isoFormat('dddd, D MMMM') }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Presensi Kelas Hari Ini & Pengajuan Menunggu -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Rekap Presensi Kelas Binaan -->
        @if($kelasWali && $rekapPresensiHariIni)
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <h4 class="font-bold text-sm text-gray-800">Presensi Hari Ini: {{ $kelasWali->nama_kelas }}</h4>
                    <a href="{{ route('guru.presensi.index') }}" class="text-xs text-teal-600 font-semibold hover:underline">
                        Input Presensi &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-5 gap-2 text-center">
                    <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-100">
                        <span class="text-base font-bold text-emerald-700">{{ $rekapPresensiHariIni['hadir'] }}</span>
                        <span class="block text-[10px] text-emerald-600 font-medium">Hadir</span>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg border border-blue-100">
                        <span class="text-base font-bold text-blue-700">{{ $rekapPresensiHariIni['izin'] }}</span>
                        <span class="block text-[10px] text-blue-600 font-medium">Izin</span>
                    </div>
                    <div class="p-2 bg-amber-50 rounded-lg border border-amber-100">
                        <span class="text-base font-bold text-amber-700">{{ $rekapPresensiHariIni['sakit'] }}</span>
                        <span class="block text-[10px] text-amber-600 font-medium">Sakit</span>
                    </div>
                    <div class="p-2 bg-rose-50 rounded-lg border border-rose-100">
                        <span class="text-base font-bold text-rose-700">{{ $rekapPresensiHariIni['alpa'] }}</span>
                        <span class="block text-[10px] text-rose-600 font-medium">Alpa</span>
                    </div>
                    <div class="p-2 bg-purple-50 rounded-lg border border-purple-100">
                        <span class="text-base font-bold text-purple-700">{{ $rekapPresensiHariIni['pulang'] }}</span>
                        <span class="block text-[10px] text-purple-600 font-medium">Pulang</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Daftar Pengajuan Membutuhkan Persetujuan Guru -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm {{ !$kelasWali ? 'lg:col-span-2' : '' }}">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                <h4 class="font-bold text-sm text-gray-800 flex items-center">
                    <span class="w-2.5 h-2.5 bg-amber-500 rounded-full mr-2"></span>
                    Pengajuan Izin & Penjemputan Siswa
                </h4>
                <a href="{{ route('guru.pengajuan.index') }}" class="text-xs text-teal-600 font-semibold hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($pengajuanMenunggu->isEmpty())
                <p class="text-xs text-gray-400 py-6 text-center italic">Tidak ada pengajuan izin atau penjemputan baru saat ini.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($pengajuanMenunggu as $aju)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div>
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $aju->jenis === 'penjemputan' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $aju->jenis)) }}
                                    </span>
                                    <span class="font-bold text-gray-800">{{ $aju->siswa->nama_lengkap }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500">
                                    Alasan: {{ $aju->alasan }}
                                    @if($aju->jam_jemput)
                                        &bull; Jam Jemput: <strong class="text-gray-700">{{ substr($aju->jam_jemput, 0, 5) }}</strong>
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('guru.pengajuan.index') }}" class="px-3 py-1 bg-teal-600 hover:bg-teal-700 text-white rounded text-xs font-semibold shadow-sm transition">
                                Proses
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-dynamic-component>
