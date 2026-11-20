<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Kelola Mutasi Masuk & Keluar Siswa</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Rekam Mutasi -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Rekam Mutasi Siswa
            </h4>

            <form method="POST" action="{{ route('admin.mutasi.store') }}" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Pilih Siswa</label>
                    <select name="siswa_id" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach($daftarSiswa as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_lengkap }} (NISN: {{ $s->nisn }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Jenis Mutasi</label>
                    <select name="jenis_mutasi" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-semibold">
                        <option value="keluar">Mutasi Keluar (Pindah ke Sekolah Lain)</option>
                        <option value="masuk">Mutasi Masuk (Pindahan dari Sekolah Lain)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Tanggal Mutasi</label>
                    <input type="date" name="tanggal_mutasi" value="{{ date('Y-m-d') }}" required
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Sekolah Asal / Tujuan</label>
                    <input type="text" name="sekolah_asal_tujuan" required placeholder="Contoh: SD Muhammadiyah Condongcatur"
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Alasan Mutasi</label>
                    <textarea name="alasan" rows="2" required placeholder="Contoh: Mengikuti perpindahan domisili tugas orang tua..."
                              class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition">
                    Simpan Catatan Mutasi
                </button>
            </form>
        </div>

        <!-- Tabel Riwayat Mutasi -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center justify-between">
                <span>Riwayat Mutasi Siswa</span>
                <span class="text-xs text-gray-500">Total: {{ $mutasi->total() }} Catatan</span>
            </h4>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700">
                    <thead class="bg-gray-50 border-b text-gray-600 uppercase text-[10px] font-semibold">
                        <tr>
                            <th class="px-3 py-2.5">Tanggal</th>
                            <th class="px-3 py-2.5">Nama Siswa</th>
                            <th class="px-3 py-2.5">Jenis</th>
                            <th class="px-3 py-2.5">Sekolah Asal / Tujuan</th>
                            <th class="px-3 py-2.5">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($mutasi as $m)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2.5 font-mono text-gray-600 whitespace-nowrap">{{ $m->tanggal_mutasi->format('d/m/Y') }}</td>
                                <td class="px-3 py-2.5 font-bold text-gray-800">{{ $m->siswa->nama_lengkap }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->jenis_mutasi === 'masuk' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        Mutasi {{ $m->jenis_mutasi }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 font-medium text-gray-700">{{ $m->sekolah_asal_tujuan }}</td>
                                <td class="px-3 py-2.5 text-gray-500 italic">{{ $m->alasan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-gray-400 italic">Belum ada data mutasi siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-t border-gray-200">
                {{ $mutasi->links() }}
            </div>
        </div>
    </div>
</x-dynamic-component>
