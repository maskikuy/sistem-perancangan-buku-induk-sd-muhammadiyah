<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Rekapitulasi Presensi Kehadiran Siswa</x-slot>

    <!-- Toolbar Filter & Tombol Cetak PDF -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.laporan.presensi') }}" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Rombel Kelas</label>
                <select name="kelas_id" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-semibold text-gray-800">
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Bulan</label>
                <select name="bulan" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create(2026, $m, 1)->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Tahun</label>
                <select name="tahun" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
                    <option value="2026" {{ $tahun == 2026 ? 'selected' : '' }}>2026</option>
                    <option value="2027" {{ $tahun == 2027 ? 'selected' : '' }}>2027</option>
                </select>
            </div>
        </form>

        <a href="{{ route('admin.laporan.presensi.cetak', ['kelas_id' => $kelasId, 'bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank"
           class="py-2 px-4 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold shadow-sm transition flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Rekap Presensi PDF
        </a>
    </div>

    <!-- Tabel Rekapitulasi Presensi -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center text-xs">
            <h4 class="font-bold text-gray-800">
                Data Kehadiran: {{ $daftarKelas->firstWhere('id', $kelasId)?->nama_kelas }} &bull; Bulan {{ Carbon\Carbon::create(2026, $bulan, 1)->isoFormat('MMMM Y') }}
            </h4>
            <span class="text-gray-500">Total Catatan: {{ $presensi->count() }} baris</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-[10px] font-semibold">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama Siswa / NISN</th>
                        <th class="px-4 py-3 text-center">Status Kehadiran</th>
                        <th class="px-4 py-3 font-mono">Waktu Masuk / Pulang</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($presensi as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2.5 font-mono text-gray-700">{{ $p->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-2.5 font-bold text-gray-800">{{ $p->siswa->nama_lengkap }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $p->status === 'hadir' ? 'bg-emerald-100 text-emerald-800' : 
                                       ($p->status === 'pulang_awal' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ str_replace('_', ' ', $p->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 font-mono text-gray-500 text-[11px]">
                                {{ $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-' }} / {{ $p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '-' }}
                            </td>
                            <td class="px-4 py-2.5 text-gray-600 italic text-[11px]">{{ $p->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Tidak ada catatan presensi pada periode bulan yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dynamic-component>
