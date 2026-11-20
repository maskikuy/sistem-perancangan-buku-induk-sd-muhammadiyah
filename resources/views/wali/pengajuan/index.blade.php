<x-dynamic-component :component="'layouts.wali-pwa'">
    <x-slot name="header">Layanan & Riwayat Pengajuan</x-slot>

    <!-- 2 Tombol Buat Pengajuan Baru -->
    <div class="grid grid-cols-2 gap-2 mb-4">
        <a href="{{ route('wali.pengajuan.izin.create') }}" 
           class="py-3 px-3 bg-white hover:bg-amber-50 rounded-2xl border border-gray-200/80 shadow-sm text-center transition active:scale-95">
            <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold mb-1.5">
                📝
            </div>
            <span class="block text-xs font-bold text-gray-800">Izin Tidak Masuk</span>
            <span class="text-[10px] text-gray-400">Sakit / Acara Keluarga</span>
        </a>

        <a href="{{ route('wali.pengajuan.penjemputan.create') }}" 
           class="py-3 px-3 bg-white hover:bg-emerald-50 rounded-2xl border border-gray-200/80 shadow-sm text-center transition active:scale-95">
            <div class="w-10 h-10 mx-auto rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold mb-1.5">
                🚗
            </div>
            <span class="block text-xs font-bold text-gray-800">Jemput Anak Pulang</span>
            <span class="text-[10px] text-gray-400">Pulang Lebih Awal</span>
        </a>
    </div>

    <!-- Daftar Riwayat Pengajuan -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-6">
        <h4 class="text-xs font-bold text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center justify-between">
            <span>Riwayat Seluruh Permohonan</span>
            <span class="text-[11px] text-gray-500 font-normal">Siswa: {{ $anak?->nama_lengkap }}</span>
        </h4>

        <div class="divide-y divide-gray-100 text-xs">
            @forelse($pengajuan as $a)
                <div class="py-3">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center space-x-1.5 mb-1">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $a->jenis === 'penjemputan' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ str_replace('_', ' ', $a->jenis) }}
                                </span>
                                @if($a->is_mendesak)
                                    <span class="text-[10px] font-bold text-rose-600">⚠️ MENDESAK</span>
                                @endif
                                <span class="font-mono text-[10px] text-gray-400">#{{ $a->nomor_pengajuan }}</span>
                            </div>
                            <p class="font-bold text-gray-800 text-xs">{{ $a->alasan }}</p>
                            
                            <div class="mt-1 text-[11px] text-gray-500 space-y-0.5">
                                <p>Tanggal: <strong class="text-gray-700">{{ $a->tanggal_mulai->format('d/m/Y') }}</strong>
                                    @if($a->jam_jemput)
                                        &bull; Jam Jemput: <strong class="text-purple-700">{{ substr($a->jam_jemput, 0, 5) }} WIB</strong>
                                    @endif
                                </p>
                                @if($a->jenis === 'penjemputan')
                                    <p>Penjemput: <strong class="text-gray-700">{{ $a->penjemputAlternatif ? $a->penjemputAlternatif->nama_lengkap . ' (' . $a->penjemputAlternatif->hubungan . ')' : 'Wali Sendiri' }}</strong></p>
                                @endif
                                @if($a->catatan_persetujuan)
                                    <p class="text-rose-600 italic">Catatan Guru: {{ $a->catatan_persetujuan }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0 ml-2">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase block
                                {{ $a->status === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : 
                                   ($a->status === 'menunggu' ? 'bg-amber-100 text-amber-800' : 
                                   ($a->status === 'selesai' ? 'bg-purple-100 text-purple-800' : 'bg-rose-100 text-rose-800')) }}">
                                {{ $a->status }}
                            </span>

                            @if($a->status === 'menunggu')
                                <form method="POST" action="{{ route('wali.pengajuan.batalkan', $a->id) }}" onsubmit="return confirm('Batalkan pengajuan ini?')" class="mt-2">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-rose-600 hover:underline">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 py-6 text-center italic">Belum ada riwayat pengajuan.</p>
            @endforelse
        </div>

        <div class="pt-3 border-t border-gray-100">
            {{ $pengajuan->links() }}
        </div>
    </div>
</x-dynamic-component>
