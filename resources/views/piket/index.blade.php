<x-dynamic-component :component="'layouts.guru'">
    <x-slot name="header">Pos Piket Gerbang: Verifikasi Penjemputan Siswa Hari Ini</x-slot>

    <!-- Header Tanggal & Info Piket -->
    <div class="bg-amber-500 text-white rounded-xl p-4 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-bold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Daftar Siswa Diizinkan Dijemput Hari Ini
            </h3>
            <p class="text-xs text-amber-100 mt-0.5">Petugas piket mencocokkan identitas penjemput sebelum menyerahkan siswa</p>
        </div>
        <x-realtime-clock variant="piket" />
    </div>

    <!-- Tabel Verifikasi Penjemputan Hari Ini -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-[10px] font-semibold">
                    <tr>
                        <th class="px-4 py-3">Rencana Jam Jemput</th>
                        <th class="px-4 py-3">Nama Siswa / Rombel</th>
                        <th class="px-4 py-3">Orang yang Ditugaskan Menjemput</th>
                        <th class="px-4 py-3">Alasan Penjemputan</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi Verifikasi Gerbang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($daftarJemput as $j)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono font-bold text-sm text-purple-700 whitespace-nowrap">
                                {{ $j->jam_jemput ? substr($j->jam_jemput, 0, 5) . ' WIB' : 'Sekarang' }}
                                @if($j->is_mendesak)
                                    <span class="block text-[10px] text-rose-600 font-bold">⚠️ Mendesak</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold text-gray-800 block text-sm">{{ $j->siswa->nama_lengkap }}</span>
                                <span class="text-[11px] text-teal-700 font-semibold">Kelas: {{ $j->siswa->kelasAktif?->kelas?->nama_kelas ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($j->penjemputAlternatif)
                                    <span class="font-bold text-gray-800 block text-xs">{{ $j->penjemputAlternatif->nama_lengkap }}</span>
                                    <span class="text-[11px] text-indigo-700 font-semibold block">Hubungan: {{ $j->penjemputAlternatif->hubungan }}</span>
                                    <span class="text-[10px] text-gray-400 font-mono">Telp: {{ $j->penjemputAlternatif->no_telepon }}</span>
                                @else
                                    <span class="font-bold text-gray-800 block text-xs">{{ $j->wali->nama_lengkap }}</span>
                                    <span class="text-[11px] text-emerald-700 font-semibold block">Orang Tua / Wali Sendiri</span>
                                    <span class="text-[10px] text-gray-400 font-mono">Telp: {{ $j->wali->no_telepon }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 max-w-xs text-xs text-gray-600">
                                {{ $j->alasan }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($j->status === 'selesai')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-purple-100 text-purple-800">
                                        Sudah Dijemput
                                    </span>
                                    <span class="block text-[10px] text-gray-400 font-mono mt-0.5">{{ $j->dijemput_pada?->format('H:i') }} WIB</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 animate-pulse">
                                        Disetujui Guru
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($j->status === 'disetujui')
                                    <form method="POST" action="{{ route('piket.penjemputan.selesai', $j->id) }}" onsubmit="return confirm('Konfirmasi penjemputan siswa ini?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition active:scale-95">
                                            ✓ Sudah Dijemput
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-gray-400 font-medium">Selesai (Oleh: {{ $j->dijemput_oleh_nama }})</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400 italic">
                                Belum ada jadwal penjemputan siswa yang disetujui untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dynamic-component>
