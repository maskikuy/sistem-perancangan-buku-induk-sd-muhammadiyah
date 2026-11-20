<x-dynamic-component :component="'layouts.wali-pwa'">
    @if(!$anak)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-2xl mb-3">
                !
            </div>
            <h3 class="text-sm font-bold text-gray-800">Akun Belum Terhubung dengan Siswa</h3>
            <p class="text-xs text-gray-500 mt-1">Silakan hubungi pihak Tata Usaha sekolah untuk mengaitkan akun Anda dengan data anak.</p>
        </div>
    @else
        <!-- Kartu Profil Anak Aktif -->
        <div class="bg-gradient-to-r from-emerald-700 to-teal-700 rounded-2xl p-4 text-white shadow-md mb-4 relative overflow-hidden">
            <div class="relative z-10 flex items-center space-x-3.5">
                <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur border border-white/30 flex items-center justify-center font-bold text-lg text-white shadow-inner flex-shrink-0">
                    {{ substr($anak->nama_lengkap, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-900/60 text-emerald-200 inline-block mb-1">
                        {{ $anak->kelasAktif?->kelas?->nama_kelas ?? 'Kelas 1' }}
                    </span>
                    <h3 class="text-sm font-bold truncate leading-tight">{{ $anak->nama_lengkap }}</h3>
                    <p class="text-[11px] text-emerald-100 font-mono mt-0.5">NISN: {{ $anak->nisn }} &bull; NIS: {{ $anak->nis }}</p>
                </div>
            </div>

            <!-- Background Decoration -->
            <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full bg-white/5 pointer-events-none"></div>
        </div>

        <!-- Banner Tambah PWA ke Layar Utama (Mobile A2HS) -->
        <div x-data="{
            deferredPrompt: null,
            showBanner: false,
            init() {
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.showBanner = true;
                });
            },
            installPWA() {
                if (this.deferredPrompt) {
                    this.deferredPrompt.prompt();
                    this.deferredPrompt.userChoice.then((choiceResult) => {
                        this.deferredPrompt = null;
                        this.showBanner = false;
                    });
                }
            }
        }" x-show="showBanner" x-cloak class="mb-4 bg-emerald-50 border border-emerald-200 rounded-2xl p-3 flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow">
                    SD
                </div>
                <div>
                    <h5 class="text-xs font-bold text-emerald-950">Pasang Aplikasi PWA</h5>
                    <p class="text-[10px] text-emerald-700">Tambahkan ke Layar Utama HP Anda</p>
                </div>
            </div>
            <button @click="installPWA()" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold shadow-sm active:scale-95 transition">
                + Pasang
            </button>
        </div>

        <!-- Kartu Kehadiran Hari Ini & Shortcut Cepat -->
        <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-gray-800">Kehadiran Hari Ini</span>
                </div>
                <x-realtime-clock variant="pwa-card" />
            </div>

            <div class="py-3 flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-gray-500 block">Status Presensi:</span>
                    @if($presensiHariIni)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase mt-1
                            {{ $presensiHariIni->status === 'hadir' ? 'bg-emerald-100 text-emerald-800' : 
                               ($presensiHariIni->status === 'pulang_awal' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ str_replace('_', ' ', $presensiHariIni->status) }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 mt-1">
                            Belum Ada Presensi
                        </span>
                    @endif
                </div>

                <div class="text-right">
                    <span class="text-[11px] text-gray-500 block">Waktu Masuk:</span>
                    <span class="text-xs font-mono font-bold text-gray-800 mt-1 block">
                        {{ $presensiHariIni?->jam_masuk ? substr($presensiHariIni->jam_masuk, 0, 5) . ' WIB' : '-' }}
                    </span>
                </div>
            </div>

            <!-- 2 Tombol Aksi Cepat (Izin & Penjemputan) -->
            <div class="pt-3 border-t border-gray-100 grid grid-cols-2 gap-2">
                <a href="{{ route('wali.pengajuan.izin.create') }}" 
                   class="flex items-center justify-center py-2.5 px-3 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-800 rounded-xl text-xs font-semibold transition active:scale-95">
                    <svg class="w-4 h-4 mr-1.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Ajukan Izin
                </a>
                <a href="{{ route('wali.pengajuan.penjemputan.create') }}" 
                   class="flex items-center justify-center py-2.5 px-3 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold transition active:scale-95">
                    <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Penjemputan
                </a>
            </div>
        </div>

        <!-- Jadwal Pelajaran Hari Ini -->
        <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100 mb-2">
                <h4 class="text-xs font-bold text-gray-800 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    Jadwal Hari Ini ({{ Carbon\Carbon::now()->isoFormat('dddd') }})
                </h4>
                <a href="{{ route('wali.jadwal') }}" class="text-[11px] text-emerald-600 font-semibold">Semua &rarr;</a>
            </div>

            @if($jadwalHariIni->isEmpty())
                <p class="text-xs text-gray-400 py-3 text-center italic">Tidak ada jadwal pelajaran untuk hari ini.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($jadwalHariIni as $j)
                        <div class="py-2 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-gray-800 block">{{ $j->mataPelajaran->nama_mapel }}</span>
                                <span class="text-[11px] text-gray-500">{{ $j->guru->nama_gelar }}</span>
                            </div>
                            <span class="text-[11px] font-mono font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded">
                                {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Riwayat Pengajuan Terbaru -->
        <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100 mb-2">
                <h4 class="text-xs font-bold text-gray-800">Status Pengajuan Terakhir</h4>
                <a href="{{ route('wali.pengajuan.index') }}" class="text-[11px] text-emerald-600 font-semibold">Riwayat &rarr;</a>
            </div>

            @if($pengajuanTerbaru->isEmpty())
                <p class="text-xs text-gray-400 py-3 text-center italic">Belum ada pengajuan izin atau penjemputan.</p>
            @else
                <div class="space-y-2">
                    @foreach($pengajuanTerbaru as $a)
                        <div class="p-2.5 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between text-xs">
                            <div>
                                <div class="flex items-center space-x-1.5">
                                    <span class="font-bold text-gray-800 capitalize">{{ str_replace('_', ' ', $a->jenis) }}</span>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $a->tanggal_mulai->format('d/m') }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 truncate max-w-[200px]">{{ $a->alasan }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $a->status === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : 
                                   ($a->status === 'menunggu' ? 'bg-amber-100 text-amber-800' : 
                                   ($a->status === 'selesai' ? 'bg-purple-100 text-purple-800' : 'bg-rose-100 text-rose-800')) }}">
                                {{ $a->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pengumuman Sekolah -->
        <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm">
            <h4 class="text-xs font-bold text-gray-800 pb-2 border-b border-gray-100 mb-2">Pengumuman Sekolah</h4>
            <div class="space-y-2.5">
                @forelse($pengumuman as $p)
                    <div class="p-2.5 rounded-xl bg-emerald-50/40 border border-emerald-100 text-xs">
                        <span class="text-[10px] text-gray-400 font-mono block">{{ $p->tanggal_terbit->format('d M Y') }}</span>
                        <h5 class="font-bold text-gray-800 text-xs mt-0.5">{{ $p->judul }}</h5>
                        <p class="text-gray-600 text-[11px] mt-1 line-clamp-2">{{ $p->isi }}</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 py-3 text-center italic">Belum ada pengumuman baru.</p>
                @endforelse
            </div>
        </div>
    @endif
</x-dynamic-component>
