<x-dynamic-component :component="'layouts.guru'">
    <x-slot name="header">Catatan Wali Kelas untuk Rapor Siswa</x-slot>

    <!-- Toolbar Kelas -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex items-center justify-between">
        <form method="GET" action="{{ route('guru.catatan.index') }}" class="flex items-center space-x-2">
            <label class="text-xs font-semibold text-gray-700">Rombel Kelas:</label>
            <select name="kelas_id" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 font-semibold text-gray-800">
                @foreach($daftarKelas as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </form>

        <span class="text-xs text-gray-500">
            Semester Aktif: <strong class="text-teal-700 font-bold capitalize">{{ $semAktif?->semester ?? 'Ganjil' }}</strong>
        </span>
    </div>

    <!-- Form Pengisian Catatan Rapor -->
    <form method="POST" action="{{ route('guru.catatan.store') }}">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b text-gray-600 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3">Catatan Perkembangan Karakter & Belajar</th>
                            <th class="px-4 py-3 w-44">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftarSiswa as $idx => $s)
                            @php
                                $row = $catatanTersimpan->get($s->id);
                                $catatanTeks = $row?->catatan;
                                $statusKenaikan = $row?->status_kenaikan ?? 'belum_ditentukan';
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-mono text-gray-500">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-bold text-gray-800 block text-sm">{{ $s->nama_lengkap }}</span>
                                    <span class="text-[11px] text-gray-400 font-mono">NISN: {{ $s->nisn }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <textarea name="catatan[{{ $s->id }}]" rows="2" placeholder="Tuliskan motivasi, perkembangan sikap ananda..."
                                              class="w-full text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">{{ $catatanTeks }}</textarea>
                                </td>
                                <td class="px-4 py-3">
                                    <select name="status_kenaikan[{{ $s->id }}]" class="w-full text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                                        <option value="belum_ditentukan" {{ $statusKenaikan === 'belum_ditentukan' ? 'selected' : '' }}>Belum Ditentukan</option>
                                        <option value="naik" {{ $statusKenaikan === 'naik' ? 'selected' : '' }}>Siap Naik Kelas</option>
                                        <option value="tinggal" {{ $statusKenaikan === 'tinggal' ? 'selected' : '' }}>Tinggal Kelas</option>
                                        <option value="lulus" {{ $statusKenaikan === 'lulus' ? 'selected' : '' }}>Lulus</option>
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400 italic">
                                    Tidak ada siswa yang terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end">
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                    Simpan Catatan Rapor
                </button>
            </div>
        </div>
    </form>
</x-dynamic-component>
