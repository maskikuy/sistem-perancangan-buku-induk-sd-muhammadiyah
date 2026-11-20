<x-dynamic-component :component="'layouts.guru'">
    <x-slot name="header">Persetujuan Pengajuan Izin & Penjemputan Siswa</x-slot>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-[10px] font-semibold">
                    <tr>
                        <th class="px-4 py-3">No. Pengajuan</th>
                        <th class="px-4 py-3">Nama Siswa / Kelas</th>
                        <th class="px-4 py-3">Jenis Permohonan</th>
                        <th class="px-4 py-3">Waktu & Penjemput</th>
                        <th class="px-4 py-3">Alasan / Bukti</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Keputusan Guru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pengajuan as $a)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono font-semibold text-gray-800">
                                {{ $a->nomor_pengajuan }}
                                <span class="block text-[10px] text-gray-400 font-sans">{{ $a->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold text-gray-800 block text-xs">{{ $a->siswa->nama_lengkap }}</span>
                                <span class="text-[11px] text-teal-700 font-medium">Kelas: {{ $a->siswa->kelasAktif?->kelas?->nama_kelas ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $a->jenis === 'penjemputan' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ str_replace('_', ' ', $a->jenis) }}
                                </span>
                                @if($a->is_mendesak)
                                    <span class="block text-[10px] font-bold text-rose-600 mt-0.5">⚠️ MENDESAK</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold text-gray-800 block">{{ $a->tanggal_mulai->format('d/m/Y') }}</span>
                                @if($a->jam_jemput)
                                    <span class="text-[11px] font-mono text-purple-700 font-bold block">Jam: {{ substr($a->jam_jemput, 0, 5) }} WIB</span>
                                @endif
                                <span class="text-[10px] text-gray-500 block">
                                    Penjemput: <strong>{{ $a->penjemputAlternatif ? $a->penjemputAlternatif->nama_lengkap . ' (' . $a->penjemputAlternatif->hubungan . ')' : 'Wali Sendiri' }}</strong>
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <p class="text-xs text-gray-800 leading-snug">{{ $a->alasan }}</p>
                                @if($a->catatan_persetujuan)
                                    <p class="text-[11px] text-gray-500 italic mt-1">Catatan: {{ $a->catatan_persetujuan }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $a->status === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : 
                                       ($a->status === 'menunggu' ? 'bg-amber-100 text-amber-800' : 
                                       ($a->status === 'selesai' ? 'bg-purple-100 text-purple-800' : 'bg-rose-100 text-rose-800')) }}">
                                    {{ $a->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($a->status === 'menunggu')
                                    <div class="flex items-center justify-center space-x-1" x-data="{ tolakOpen: false }">
                                        <!-- Tombol Setuju -->
                                        <form method="POST" action="{{ route('guru.pengajuan.proses', $a->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-semibold shadow-sm transition">
                                                Setujui
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak Modal -->
                                        <button type="button" @click="tolakOpen = true" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded text-[11px] font-semibold transition">
                                            Tolak
                                        </button>

                                        <!-- Modal Tolak Alasan Wajib -->
                                        <div x-show="tolakOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                                            <div class="bg-white rounded-xl max-w-sm w-full p-4 text-left shadow-2xl">
                                                <h5 class="font-bold text-sm text-gray-800 mb-2">Alasan Penolakan (Wajib Diisi)</h5>
                                                <form method="POST" action="{{ route('guru.pengajuan.proses', $a->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <textarea name="catatan" rows="3" required placeholder="Contoh: Sedang ada penilaian ujian tengah semester..."
                                                              class="w-full text-xs rounded-lg border-gray-300 focus:ring-rose-500 focus:border-rose-500 mb-3"></textarea>
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="button" @click="tolakOpen = false" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-300 rounded-lg">Batal</button>
                                                        <button type="submit" class="px-3 py-1.5 text-xs bg-rose-600 text-white font-semibold rounded-lg">Konfirmasi Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[11px] text-gray-400 font-medium">Selesai Diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400 italic">
                                Belum ada pengajuan izin atau penjemputan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $pengajuan->links() }}
        </div>
    </div>
</x-dynamic-component>
