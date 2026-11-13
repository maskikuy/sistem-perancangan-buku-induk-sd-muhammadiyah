<x-dynamic-component :component="'layouts.guru'">
    <x-slot name="header">Input Presensi Harian Siswa</x-slot>

    <!-- Toolbar Pemilih Kelas & Tanggal -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('guru.presensi.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Rombel Kelas</label>
                    <select name="kelas_id" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 font-semibold text-gray-800">
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Presensi</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                           class="text-xs rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 font-mono">
                </div>
            </div>

            <!-- Tombol Tandai Hadir Semua via JS -->
            <button type="button" 
                    onclick="document.querySelectorAll('input[value=hadir]').forEach(el => el.checked = true)"
                    class="py-2 px-3 bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 rounded-lg text-xs font-semibold transition">
                ✓ Tandai Semua Hadir
            </button>
        </form>
    </div>

    <!-- Form Pengisian Presensi -->
    <form method="POST" action="{{ route('guru.presensi.store') }}">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b text-gray-600 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">NISN / Nama Siswa</th>
                            <th class="px-4 py-3 text-center">Status Kehadiran</th>
                            <th class="px-4 py-3">Catatan / Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($daftarSiswa as $idx => $s)
                            @php
                                $statusTercatat = $presensiTercatat->get($s->id)?->status ?? 'hadir';
                                $ketTercatat = $presensiTercatat->get($s->id)?->keterangan;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-mono text-gray-500">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-bold text-gray-800 block text-sm">{{ $s->nama_lengkap }}</span>
                                    <span class="text-[11px] text-gray-400 font-mono">NISN: {{ $s->nisn }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center space-x-3">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="presensi[{{ $s->id }}]" value="hadir" {{ $statusTercatat === 'hadir' ? 'checked' : '' }}
                                                   class="text-teal-600 focus:ring-teal-500">
                                            <span class="ml-1 text-xs font-semibold text-emerald-700">Hadir</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="presensi[{{ $s->id }}]" value="izin" {{ $statusTercatat === 'izin' ? 'checked' : '' }}
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-1 text-xs font-semibold text-blue-700">Izin</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="presensi[{{ $s->id }}]" value="sakit" {{ $statusTercatat === 'sakit' ? 'checked' : '' }}
                                                   class="text-amber-600 focus:ring-amber-500">
                                            <span class="ml-1 text-xs font-semibold text-amber-700">Sakit</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="presensi[{{ $s->id }}]" value="alpa" {{ $statusTercatat === 'alpa' ? 'checked' : '' }}
                                                   class="text-rose-600 focus:ring-rose-500">
                                            <span class="ml-1 text-xs font-semibold text-rose-700">Alpa</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="presensi[{{ $s->id }}]" value="pulang_awal" {{ $statusTercatat === 'pulang_awal' ? 'checked' : '' }}
                                                   class="text-purple-600 focus:ring-purple-500">
                                            <span class="ml-1 text-xs font-semibold text-purple-700">Pulang</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="keterangan[{{ $s->id }}]" value="{{ $ketTercatat }}" placeholder="Opsional..."
                                           class="w-full text-xs rounded border-gray-300 focus:ring-teal-500 focus:border-teal-500 py-1 px-2">
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
                <span class="text-xs text-gray-500">Total: {{ $daftarSiswa->count() }} siswa</span>
                <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                    Simpan Presensi Hari Ini
                </button>
            </div>
        </div>
    </form>
</x-dynamic-component>
