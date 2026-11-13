<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Kelola Mata Pelajaran & Kurikulum Ismuba</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Mapel -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Tambah Mapel Baru
            </h4>

            <form method="POST" action="{{ route('admin.mapel.store') }}" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Kode Mapel</label>
                    <input type="text" name="kode_mapel" required placeholder="Contoh: KMD-02"
                           class="w-full text-xs rounded-lg border-gray-300 font-mono uppercase focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" required placeholder="Contoh: Kemuhammadiyahan"
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Kelompok Mapel</label>
                    <select name="kelompok" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="ciri_khusus_muhammadiyah">Ciri Khusus Muhammadiyah (Ismuba)</option>
                        <option value="umum">Mata Pelajaran Umum (Nasional)</option>
                        <option value="muatan_lokal">Muatan Lokal (Bahasa Jawa / DIY)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Kriteria Ketuntasan Minimal (KKM)</label>
                    <input type="number" step="0.5" name="kkm" value="75.0" min="0" max="100" required
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition">
                    Simpan Mata Pelajaran
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Mapel -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center justify-between">
                <span>Daftar Mata Pelajaran</span>
                <span class="text-xs text-gray-500 font-normal">Total: {{ $mapel->count() }} Mapel</span>
            </h4>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b text-gray-600 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="px-3 py-2.5">Kode</th>
                            <th class="px-3 py-2.5">Nama Mata Pelajaran</th>
                            <th class="px-3 py-2.5">Kelompok</th>
                            <th class="px-3 py-2.5 text-center">KKM</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($mapel as $m)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2.5 font-mono font-bold text-gray-800">{{ $m->kode_mapel }}</td>
                                <td class="px-3 py-2.5 font-semibold text-gray-800">{{ $m->nama_mapel }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $m->kelompok === 'ciri_khusus_muhammadiyah' ? 'bg-emerald-100 text-emerald-800' : ($m->kelompok === 'muatan_lokal' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucwords(str_replace('_', ' ', $m->kelompok)) }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-center font-bold text-emerald-700">{{ number_format($m->kkm, 1) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dynamic-component>
