<x-dynamic-component :component="'layouts.wali-pwa'">
    <x-slot name="header">Jadwal Pelajaran: {{ $anak->nama_lengkap }}</x-slot>

    <!-- Header Kelas -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4 flex items-center justify-between text-xs">
        <div>
            <span class="text-gray-400 block text-[10px]">Kelas Ananda:</span>
            <span class="font-bold text-gray-800 text-sm">{{ $kelasAktif?->nama_kelas ?? 'Belum ada kelas' }}</span>
        </div>
        <span class="text-[11px] text-gray-500 font-medium">
            Wali Kelas: <strong class="text-emerald-700">{{ $kelasAktif?->waliKelas?->nama_gelar ?? '-' }}</strong>
        </span>
    </div>

    <!-- Accordion / Card per Hari -->
    <div class="space-y-3 mb-6">
        @php
            $hariList = ['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu'];
            $hariIni = strtolower(Carbon\Carbon::now()->isoFormat('dddd'));
        @endphp

        @foreach($hariList as $hariKey => $hariNama)
            @php 
                $listJadwal = $jadwal->get($hariKey, collect()); 
                $isToday = $hariKey === $hariIni;
            @endphp
            <div class="bg-white rounded-2xl border {{ $isToday ? 'border-emerald-500 shadow-md ring-1 ring-emerald-500' : 'border-gray-200/80 shadow-sm' }} overflow-hidden text-xs">
                <div class="px-4 py-2.5 {{ $isToday ? 'bg-emerald-600 text-white' : 'bg-gray-50 text-gray-800' }} flex items-center justify-between font-bold">
                    <span class="flex items-center">
                        @if($isToday)
                            <span class="w-2 h-2 rounded-full bg-white mr-1.5 animate-pulse"></span>
                        @endif
                        {{ $hariNama }}
                        @if($isToday) <span class="text-[10px] ml-1.5 font-normal opacity-90">(Hari Ini)</span> @endif
                    </span>
                    <span class="text-[10px] font-mono font-medium {{ $isToday ? 'text-emerald-100' : 'text-gray-400' }}">
                        {{ $listJadwal->count() }} Pelajaran
                    </span>
                </div>

                <div class="p-3 divide-y divide-gray-100">
                    @forelse($listJadwal as $j)
                        <div class="py-2 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-gray-800 block text-xs">{{ $j->mataPelajaran->nama_mapel }}</span>
                                <span class="text-[11px] text-gray-500">{{ $j->guru->nama_gelar }}</span>
                            </div>
                            <span class="font-mono text-[11px] font-medium bg-gray-50 border border-gray-100 px-2 py-0.5 rounded text-gray-700">
                                {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                            </span>
                        </div>
                    @empty
                        <p class="py-2.5 text-center text-gray-400 italic text-[11px]">Tidak ada jam pelajaran.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-dynamic-component>
