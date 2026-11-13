<x-dynamic-component :component="'layouts.guru'">
    <x-slot name="header">Input Nilai Akademik Siswa</x-slot>

    <!-- Toolbar Filter -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('guru.nilai.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Rombel Kelas</label>
                <select name="kelas_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 font-semibold text-gray-800">
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Mata Pelajaran</label>
                <select name="mapel_id" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 font-semibold text-gray-800">
                    @foreach($daftarMapel as $m)
                        <option value="{{ $m->id }}" {{ $m->id == $mapelId ? 'selected' : '' }}>
                            {{ $m->nama_mapel }} (KKM: {{ $m->kkm }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Penilaian</label>
                <select name="jenis_nilai" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 font-semibold text-gray-800 uppercase">
                    <option value="tugas" {{ $jenisNilai === 'tugas' ? 'selected' : '' }}>Tugas / Formatif</option>
                    <option value="uh" {{ $jenisNilai === 'uh' ? 'selected' : '' }}>Ulangan Harian (UH)</option>
                    <option value="pts" {{ $jenisNilai === 'pts' ? 'selected' : '' }}>Penilaian Tengah Semester (PTS)</option>
                    <option value="pas" {{ $jenisNilai === 'pas' ? 'selected' : '' }}>Penilaian Akhir Semester (PAS)</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Form Input Nilai Se-kelas -->
    <form method="POST" action="{{ route('guru.nilai.store') }}">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
        <input type="hidden" name="mata_pelajaran_id" value="{{ $mapelId }}">
        <input type="hidden" name="jenis_nilai" value="{{ $jenisNilai }}">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b text-gray-600 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Lengkap Siswa</th>
                            <th class="px-4 py-3 w-32 text-center">Nilai Angka (0-100)</th>
                            <th class="px-4 py-3">Deskripsi Capaian Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftarSiswa as $idx => $s)
                            @php
                                $nilaiRow = $nilaiTersimpan->get($s->id);
                                $nilaiAngka = $nilaiRow?->nilai_angka;
                                $capaian = $nilaiRow?->capaian_kompetensi;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-mono text-gray-500">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-bold text-gray-800 block text-sm">{{ $s->nama_lengkap }}</span>
                                    <span class="text-[11px] text-gray-400 font-mono">NISN: {{ $s->nisn }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <input type="number" step="0.5" min="0" max="100" 
                                           name="nilai[{{ $s->id }}]" 
                                           value="{{ $nilaiAngka }}" 
                                           placeholder="85.0"
                                           class="w-24 text-center font-bold text-sm rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 py-1.5 font-mono">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" 
                                           name="capaian[{{ $s->id }}]" 
                                           value="{{ $capaian }}" 
                                           placeholder="Contoh: Sangat baik dalam memahami materi..."
                                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 py-1.5 px-3">
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

            <div class="p-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <span class="text-xs text-gray-500">Mata Pelajaran: <strong class="text-gray-800">{{ $daftarMapel->firstWhere('id', $mapelId)?->nama_mapel }}</strong></span>
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                    Simpan Nilai Kelas
                </button>
            </div>
        </div>
    </form>
</x-dynamic-component>
