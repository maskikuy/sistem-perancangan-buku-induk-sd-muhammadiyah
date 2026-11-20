<x-dynamic-component :component="'layouts.guru'">
    <x-slot name="header">Portal Pendidik: {{ $guru?->nama_gelar ?? auth()->user()->name }}</x-slot>

    <!-- Banner Akademik Guru & Tahun Ajaran Aktif -->
    <div style="background: linear-gradient(135deg, #115e59 0%, #065f46 100%);" class="bg-teal-800 rounded-2xl p-5 text-white shadow-md mb-6 relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-teal-900/90 text-teal-200 border border-teal-600/50 flex items-center">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        TA Aktif: {{ $taAktif?->nama ?? '2026/2027' }} (Semester {{ ucfirst($semAktif?->semester ?? 'Ganjil') }})
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-900/90 text-emerald-200 border border-emerald-600/50">
                        {{ $kelasWali ? 'Wali Kelas ' . $kelasWali->nama_kelas : 'Guru Mata Pelajaran' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono bg-white/10 text-teal-100 border border-white/20">
                        NIP: {{ $guru?->nip ?? '-' }}
                    </span>
                </div>
                <h2 class="text-xl font-bold leading-tight">Selamat Datang, {{ $guru?->nama_gelar ?? auth()->user()->name }}</h2>
                <p class="text-xs text-teal-100 mt-1">
                    SD Muhammadiyah Sleman &bull; Bertugas mengampu {{ $mapelDiampu->count() }} mata pelajaran dengan total beban {{ $totalJadwalMingguan }} jam pelajaran/minggu.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
                <a href="{{ route('guru.jadwal.index') }}" class="px-3.5 py-2 bg-white/15 hover:bg-white/25 backdrop-blur rounded-xl text-xs font-semibold border border-white/20 text-white transition flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-1.5 text-teal-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Jadwal Mengajar
                </a>
                <a href="{{ route('guru.presensi.index') }}" class="px-3.5 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-xl text-xs font-semibold shadow-sm transition flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Input Presensi
                </a>
            </div>
        </div>
        <!-- Hiasan Background Lingkaran -->
        <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
    </div>

    <!-- 4 Kartu Informasi Ringkasan Akademik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Kelas Perwalian -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas Binaan</p>
                <h3 class="text-lg font-bold text-gray-800 mt-1 truncate max-w-[150px]" title="{{ $kelasWali ? $kelasWali->nama_kelas : 'Bukan Wali Kelas' }}">
                    {{ $kelasWali ? $kelasWali->nama_kelas : 'Guru Mapel' }}
                </h3>
                <p class="text-[11px] text-teal-600 mt-1 font-medium">
                    {{ $totalSiswaWali }} Siswa Terdaftar
                </p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>

        <!-- Mata Pelajaran Diampu -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Mapel Diampu</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $mapelDiampu->count() }} Mapel</h3>
                <p class="text-[11px] text-emerald-600 mt-1 font-medium">Kurikulum Ismuba & Umum</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <!-- Beban Mengajar Mingguan -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Beban Mengajar</p>
                <h3 class="text-2xl font-bold text-indigo-700 mt-1">{{ $totalJadwalMingguan }} JP</h3>
                <p class="text-[11px] text-indigo-600 mt-1 font-medium">Jam Pelajaran / Minggu</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Pengajuan Menunggu Verifikasi -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Persetujuan Izin</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $pengajuanMenunggu->count() }}</h3>
                <p class="text-[11px] text-gray-500 mt-1">Izin & Penjemputan</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Dua Kolom: Jadwal Mengajar Hari Ini & Presensi / Pengajuan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Jadwal Mengajar Hari Ini -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                    <h4 class="font-bold text-sm text-gray-800 flex items-center">
                        <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full mr-2"></span>
                        Jadwal Mengajar Hari Ini ({{ Carbon\Carbon::now()->isoFormat('dddd') }})
                    </h4>
                    <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">
                        {{ Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
                    </span>
                </div>

                @if($jadwalHariIni->isEmpty())
                    <div class="py-8 text-center">
                        <div class="w-12 h-12 mx-auto rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-700">Tidak Ada Jam Mengajar Hari Ini</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">Hari ini tidak ada jadwal tatap muka. Anda memiliki total {{ $totalJadwalMingguan }} JP di hari lainnya.</p>
                    </div>
                @else
                    <div class="space-y-2.5">
                        @foreach($jadwalHariIni as $j)
                            <div class="p-3 rounded-xl bg-teal-50/60 border border-teal-100 flex items-center justify-between text-xs hover:bg-teal-50 transition">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-teal-600 text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                                        {{ substr($j->mataPelajaran->kode_mapel ?? 'MP', 0, 3) }}
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-gray-900 text-xs sm:text-sm">{{ $j->mataPelajaran->nama_mapel }}</h5>
                                        <p class="text-[11px] text-teal-800 font-medium">Kelas: <strong>{{ $j->kelas->nama_kelas }}</strong></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-mono font-bold text-teal-900 block">
                                        {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                    </span>
                                    <span class="text-[10px] text-teal-700 font-medium">WIB</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="pt-4 border-t border-gray-100 mt-4 flex items-center justify-between">
                <span class="text-[11px] text-gray-500">Lihat distribusi jadwal 1 minggu penuh</span>
                <a href="{{ route('guru.jadwal.index') }}" class="text-xs text-teal-600 font-bold hover:underline flex items-center">
                    Jadwal Lengkap Mingguan &rarr;
                </a>
            </div>
        </div>

        <!-- Rekap Presensi Kelas Binaan / Pengajuan Menunggu -->
        <div class="space-y-6">
            @if($kelasWali && $rekapPresensiHariIni)
                <!-- Rekap Presensi Kelas Binaan -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                        <div>
                            <h4 class="font-bold text-sm text-gray-800">Presensi Hari Ini: {{ $kelasWali->nama_kelas }}</h4>
                            <p class="text-[11px] text-gray-500">Tahun Ajaran {{ $taAktif?->nama ?? '2026/2027' }}</p>
                        </div>
                        <a href="{{ route('guru.presensi.index') }}" class="text-xs text-teal-600 font-bold hover:underline">
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
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                    <h4 class="font-bold text-sm text-gray-800 flex items-center">
                        <span class="w-2.5 h-2.5 bg-amber-500 rounded-full mr-2"></span>
                        Pengajuan Izin & Penjemputan
                    </h4>
                    <a href="{{ route('guru.pengajuan.index') }}" class="text-xs text-teal-600 font-bold hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if($pengajuanMenunggu->isEmpty())
                    <p class="text-xs text-gray-400 py-4 text-center italic">Tidak ada pengajuan izin atau penjemputan baru saat ini.</p>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($pengajuanMenunggu->take(3) as $aju)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div>
                                    <div class="flex items-center space-x-2 mb-0.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $aju->jenis === 'penjemputan' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $aju->jenis)) }}
                                        </span>
                                        <span class="font-bold text-gray-800">{{ $aju->siswa->nama_lengkap }}</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500">
                                        Alasan: {{ $aju->alasan }}
                                        @if($aju->jam_jemput)
                                            &bull; Jam: <strong class="text-gray-700">{{ substr($aju->jam_jemput, 0, 5) }}</strong>
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('guru.pengajuan.index') }}" class="px-2.5 py-1 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                    Proses
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mata Pelajaran yang Diampu Guru Ini (Realitas Akademik) -->
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <div>
                <h4 class="font-bold text-sm text-gray-800 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Mata Pelajaran yang Diampu (Tahun Ajaran {{ $taAktif?->nama ?? '2026/2027' }})
                </h4>
                <p class="text-[11px] text-gray-500">Daftar mata pelajaran yang diajarkan oleh pendidik berdasarkan penugasan jadwal resmi sekolah</p>
            </div>
            <a href="{{ route('guru.nilai.index') }}" class="text-xs text-teal-600 font-bold hover:underline">
                Kelola Nilai Rapor &rarr;
            </a>
        </div>

        @if($mapelDiampu->isEmpty())
            <p class="text-xs text-gray-400 py-4 text-center italic">Belum ada mata pelajaran yang ditugaskan ke pendidik ini.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($mapelDiampu as $m)
                    <div class="p-3 rounded-xl border border-gray-200/90 bg-gray-50/50 hover:bg-teal-50/50 hover:border-teal-200 transition group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono bg-white border text-gray-600">
                                    {{ $m->kode_mapel }}
                                </span>
                                <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    KKM: {{ $m->kkm }}
                                </span>
                            </div>
                            <h5 class="font-bold text-xs text-gray-900 group-hover:text-teal-800 transition leading-snug">
                                {{ $m->nama_mapel }}
                            </h5>
                            <span class="text-[10px] text-gray-500 capitalize block mt-1">
                                Kelompok: {{ str_replace('_', ' ', $m->kelompok) }}
                            </span>
                        </div>
                        <div class="pt-3 mt-2 border-t border-gray-200/60 flex items-center justify-between text-[11px]">
                            <a href="{{ route('guru.nilai.index', ['mapel_id' => $m->id]) }}" class="text-teal-700 font-semibold hover:underline">
                                Input Nilai &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-dynamic-component>
