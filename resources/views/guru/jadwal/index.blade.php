<x-dynamic-component :component="'layouts.guru'">
    <x-slot name="header">Jadwal Mengajar Pribadi: {{ $guru?->nama_gelar }}</x-slot>

    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex justify-between items-center text-xs">
        <span class="text-gray-500">
            Tahun Ajaran: <strong class="text-teal-700">{{ $taAktif?->nama ?? '-' }}</strong> &bull; Semester: <strong class="text-teal-700 capitalize">{{ $semAktif?->semester ?? 'Ganjil' }}</strong>
        </span>
        <span class="font-mono text-gray-500">NIP: {{ $guru?->nip ?? '-' }}</span>
    </div>

    <!-- Grid Hari Jadwal Mengajar -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        @endphp

        @foreach($hariList as $hari)
            @php $jadwalHari = $jadwal->get($hari, collect()); @endphp
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-teal-700 text-white px-4 py-2.5 font-bold text-xs uppercase tracking-wider flex justify-between items-center">
                    <span>{{ $hari }}</span>
                    <span class="text-[10px] bg-teal-800 px-2 py-0.5 rounded-full">{{ $jadwalHari->count() }} Jam</span>
                </div>

                <div class="p-3 divide-y divide-gray-100 text-xs">
                    @forelse($jadwalHari as $j)
                        <div class="py-2.5 flex items-start justify-between">
                            <div>
                                <span class="font-bold text-gray-800 block text-xs">{{ $j->mataPelajaran->nama_mapel }}</span>
                                <span class="text-[11px] text-teal-700 font-semibold">Kelas: {{ $j->kelas->nama_kelas }}</span>
                            </div>
                            <span class="font-mono text-[11px] text-gray-500 bg-gray-100 px-2 py-0.5 rounded whitespace-nowrap">
                                {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                            </span>
                        </div>
                    @empty
                        <p class="py-4 text-center text-gray-400 italic text-[11px]">Tidak ada jadwal mengajar.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-dynamic-component>
