<x-dynamic-component :component="'layouts.wali-pwa'">
    <x-slot name="header">Kehadiran: {{ $anak->nama_lengkap }}</x-slot>

    <!-- Filter Bulan & Tahun -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4">
        <form method="GET" action="{{ route('wali.presensi') }}" class="grid grid-cols-2 gap-2 text-xs">
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Bulan</label>
                <select name="bulan" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create(2026, $m, 1)->isoFormat('MMMM') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Tahun</label>
                <select name="tahun" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
                    <option value="2026" {{ $tahun == 2026 ? 'selected' : '' }}>2026</option>
                    <option value="2027" {{ $tahun == 2027 ? 'selected' : '' }}>2027</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Ringkasan Statistik Kehadiran Bulan Ini -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4">
        <h4 class="text-xs font-bold text-gray-800 pb-2 border-b border-gray-100 mb-3">
            Rekap Kehadiran Bulan {{ Carbon\Carbon::create(2026, $bulan, 1)->isoFormat('MMMM') }}
        </h4>

        <div class="grid grid-cols-5 gap-1.5 text-center text-xs">
            <div class="p-2 bg-emerald-50 rounded-xl border border-emerald-100">
                <span class="text-base font-bold text-emerald-700 block">{{ $rekap['hadir'] }}</span>
                <span class="text-[10px] text-emerald-600 font-medium">Hadir</span>
            </div>
            <div class="p-2 bg-blue-50 rounded-xl border border-blue-100">
                <span class="text-base font-bold text-blue-700 block">{{ $rekap['izin'] }}</span>
                <span class="text-[10px] text-blue-600 font-medium">Izin</span>
            </div>
            <div class="p-2 bg-amber-50 rounded-xl border border-amber-100">
                <span class="text-base font-bold text-amber-700 block">{{ $rekap['sakit'] }}</span>
                <span class="text-[10px] text-amber-600 font-medium">Sakit</span>
            </div>
            <div class="p-2 bg-rose-50 rounded-xl border border-rose-100">
                <span class="text-base font-bold text-rose-700 block">{{ $rekap['alpa'] }}</span>
                <span class="text-[10px] text-rose-600 font-medium">Alpa</span>
            </div>
            <div class="p-2 bg-purple-50 rounded-xl border border-purple-100">
                <span class="text-base font-bold text-purple-700 block">{{ $rekap['pulang'] }}</span>
                <span class="text-[10px] text-purple-600 font-medium">Pulang</span>
            </div>
        </div>
    </div>

    <!-- Riwayat Catatan Presensi Harian -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm">
        <h4 class="text-xs font-bold text-gray-800 pb-2 border-b border-gray-100 mb-3">Log Presensi Harian</h4>

        <div class="divide-y divide-gray-100 text-xs">
            @forelse($presensi as $p)
                <div class="py-2.5 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-gray-800 block text-xs">{{ $p->tanggal->isoFormat('dddd, D MMMM Y') }}</span>
                        @if($p->jam_masuk)
                            <span class="text-[11px] text-gray-400 font-mono">Masuk: {{ substr($p->jam_masuk, 0, 5) }} WIB</span>
                        @endif
                        @if($p->keterangan)
                            <span class="text-[11px] text-gray-500 italic block">{{ $p->keterangan }}</span>
                        @endif
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                        {{ $p->status === 'hadir' ? 'bg-emerald-100 text-emerald-800' : 
                           ($p->status === 'pulang_awal' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ str_replace('_', ' ', $p->status) }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-gray-400 py-6 text-center italic">Tidak ada catatan presensi pada bulan ini.</p>
            @endforelse
        </div>
    </div>
</x-dynamic-component>
