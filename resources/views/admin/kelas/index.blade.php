<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Kelola Rombongan Belajar (Kelas) & Wali Kelas</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Kelas -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Tambah Kelas Baru
            </h4>

            <form method="POST" action="{{ route('admin.kelas.store') }}" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Nama Rombel Kelas</label>
                    <input type="text" name="nama_kelas" required placeholder="Contoh: 1 Abu Bakar"
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Tingkat Kelas (1-6)</label>
                    <select name="tingkat" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        @for($i=1; $i<=6; $i++)
                            <option value="{{ $i }}">Tingkat / Kelas {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Wali Kelas (Guru)</label>
                    <select name="guru_id" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_gelar }} (NIP: {{ $g->nip ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Kapasitas Maksimal Siswa</label>
                    <input type="number" name="kapasitas" value="28" min="1" required
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition">
                    Simpan Rombel Kelas
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Kelas -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center justify-between">
                <span>Daftar Rombongan Belajar (TA: {{ $taAktif?->nama ?? '-' }})</span>
                <span class="text-xs text-gray-500 font-normal">Total: {{ $kelas->count() }} Kelas</span>
            </h4>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b text-gray-600 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="px-3 py-2.5">Tingkat</th>
                            <th class="px-3 py-2.5">Nama Kelas</th>
                            <th class="px-3 py-2.5">Wali Kelas</th>
                            <th class="px-3 py-2.5 text-center">Siswa / Kapasitas</th>
                            <th class="px-3 py-2.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($kelas as $k)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2.5 font-bold text-gray-800">Kelas {{ $k->tingkat }}</td>
                                <td class="px-3 py-2.5 font-semibold text-emerald-800">{{ $k->nama_kelas }}</td>
                                <td class="px-3 py-2.5 text-gray-700">{{ $k->waliKelas?->nama_gelar ?? '-' }}</td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="px-2 py-0.5 rounded-full font-bold {{ $k->riwayat_kelas_count >= $k->kapasitas ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $k->riwayat_kelas_count }} / {{ $k->kapasitas }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <a href="{{ route('admin.siswa.index', ['kelas_id' => $k->id]) }}" 
                                       class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded border border-emerald-200 text-[11px] font-medium">
                                        Lihat Siswa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-gray-400 italic">Belum ada kelas yang didaftarkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dynamic-component>
