<x-dynamic-component :component="'layouts.wali-pwa'">
    <x-slot name="header">Rapor Akademik: {{ $anak->nama_lengkap }}</x-slot>

    <!-- Header Semester -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4 flex items-center justify-between text-xs">
        <div>
            <span class="text-gray-400 block text-[10px]">Tahun Ajaran / Semester:</span>
            <span class="font-bold text-gray-800 text-sm">{{ $taAktif?->nama ?? '2026/2027' }} ({{ ucfirst($semAktif?->semester ?? 'ganjil') }})</span>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
            {{ $anak->kelasAktif?->kelas?->nama_kelas ?? 'Kelas 1' }}
        </span>
    </div>

    <!-- Catatan Wali Kelas -->
    @if($catatanWali)
        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-4 shadow-sm mb-4 text-xs">
            <h4 class="font-bold text-emerald-900 pb-1.5 border-b border-emerald-200/60 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                Catatan Wali Kelas
            </h4>
            <p class="text-emerald-950 font-medium leading-relaxed italic">
                "{{ $catatanWali->catatan }}"
            </p>
            @if($catatanWali->status_kenaikan !== 'belum_ditentukan')
                <div class="mt-2 pt-2 border-t border-emerald-200/60 flex items-center justify-between">
                    <span class="text-[11px] text-emerald-800 font-medium">Keputusan Akhir:</span>
                    <span class="px-2 py-0.5 rounded font-bold text-[10px] bg-emerald-700 text-white uppercase">
                        {{ $catatanWali->status_kenaikan }}
                    </span>
                </div>
            @endif
        </div>
    @endif

    <!-- Daftar Nilai Mata Pelajaran -->
    <div class="space-y-3 mb-6">
        @forelse($nilai as $mapelId => $nilaiGroup)
            @php $mapel = $nilaiGroup->first()->mataPelajaran; @endphp
            <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm text-xs">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100 mb-2.5">
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">{{ $mapel->nama_mapel }}</h4>
                        <span class="text-[10px] text-gray-400 font-mono">{{ $mapel->kode_mapel }} &bull; KKM: {{ $mapel->kkm }}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $mapel->kelompok === 'ciri_khusus_muhammadiyah' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                        {{ $mapel->kelompok === 'ciri_khusus_muhammadiyah' ? 'Ismuba' : 'Umum' }}
                    </span>
                </div>

                <!-- Nilai Per Jenis (Tugas, UH, PTS, PAS) -->
                <div class="grid grid-cols-4 gap-1.5 text-center text-xs mb-2.5">
                    @foreach(['tugas' => 'Tugas', 'uh' => 'UH', 'pts' => 'PTS', 'pas' => 'PAS'] as $key => $lbl)
                        @php $val = $nilaiGroup->firstWhere('jenis_nilai', $key); @endphp
                        <div class="p-1.5 bg-gray-50 rounded-xl">
                            <span class="text-[10px] text-gray-400 block">{{ $lbl }}</span>
                            <span class="font-bold font-mono text-sm {{ $val && $val->nilai_angka >= $mapel->kkm ? 'text-emerald-700' : 'text-gray-800' }}">
                                {{ $val ? number_format($val->nilai_angka, 1) : '-' }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Capaian Kompetensi -->
                @php $latestCapaian = $nilaiGroup->whereNotNull('capaian_kompetensi')->first(); @endphp
                @if($latestCapaian)
                    <div class="p-2.5 bg-gray-50/70 rounded-xl text-[11px] text-gray-600">
                        <strong class="text-gray-700 block text-[10px] uppercase font-semibold">Capaian Pembelajaran:</strong>
                        {{ $latestCapaian->capaian_kompetensi }}
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center text-xs text-gray-400 italic">
                Belum ada nilai yang diinputkan untuk semester ini.
            </div>
        @endforelse
    </div>
</x-dynamic-component>
