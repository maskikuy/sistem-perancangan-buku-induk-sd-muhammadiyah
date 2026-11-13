<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Kelola Jadwal Pelajaran Sekolah</x-slot>

    <!-- Filter Kelas Aktif -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex items-center space-x-2 w-full sm:w-auto">
            <label class="text-xs font-semibold text-gray-700 whitespace-nowrap">Pilih Rombel Kelas:</label>
            <select name="kelas_id" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-semibold text-emerald-800">
                @foreach($daftarKelas as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasId ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} (Wali: {{ $k->waliKelas?->nama_lengkap ?? '-' }})
                    </option>
                @endforeach
            </select>
        </form>

        <span class="text-xs text-gray-500">
            Tahun Ajaran: <strong class="text-emerald-700">{{ $taAktif?->nama ?? '-' }} (Semester {{ $semAktif?->semester ?? 'Ganjil' }})</strong>
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Jadwal -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Tambah Jam Pelajaran
            </h4>

            <form method="POST" action="{{ route('admin.jadwal.store') }}" class="space-y-3 text-xs">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Hari</label>
                    <select name="hari" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="senin">Senin</option>
                        <option value="selasa">Selasa</option>
                        <option value="rabu">Rabu</option>
                        <option value="kamis">Kamis</option>
                        <option value="jumat">Jum'at</option>
                        <option value="sabtu">Sabtu</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="mata_pelajaran_id" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach($daftarMapel as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_mapel }} ({{ $m->kode_mapel }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Guru Pengampu</label>
                    <select name="guru_id" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_gelar }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition">
                    Tambahkan ke Jadwal
                </button>
            </form>
        </div>

        <!-- Tabel Jadwal Mingguan -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center justify-between">
                <span>Jadwal Pelajaran Mingguan</span>
                <span class="text-xs text-emerald-700 font-bold">
                    {{ $daftarKelas->firstWhere('id', $kelasId)?->nama_kelas ?? 'Kelas' }}
                </span>
            </h4>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b text-gray-600 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="px-3 py-2.5">Hari</th>
                            <th class="px-3 py-2.5">Jam Pelajaran</th>
                            <th class="px-3 py-2.5">Mata Pelajaran</th>
                            <th class="px-3 py-2.5">Guru Pengampu</th>
                            <th class="px-3 py-2.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($jadwal as $j)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2.5 font-bold uppercase text-[10px] text-gray-700">{{ $j->hari }}</td>
                                <td class="px-3 py-2.5 font-mono text-gray-600 whitespace-nowrap">
                                    {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}
                                </td>
                                <td class="px-3 py-2.5 font-semibold text-gray-800">
                                    {{ $j->mataPelajaran->nama_mapel }}
                                    <span class="text-[10px] text-gray-400 font-mono block">{{ $j->mataPelajaran->kode_mapel }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-gray-700">{{ $j->guru->nama_gelar }}</td>
                                <td class="px-3 py-2.5 text-center">
                                    <form method="POST" action="{{ route('admin.jadwal.destroy', $j->id) }}" onsubmit="return confirm('Hapus jam pelajaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 text-[11px] font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-gray-400 italic">Belum ada jadwal pelajaran untuk kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dynamic-component>
