<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Buku Induk Digital Siswa</x-slot>

    <!-- Toolbar Atas: Filter & Tambah Siswa -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Pencarian -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Cari Siswa</label>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Ketik Nama, NISN, atau NIS..."
                       class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Filter Kelas -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Rombel Kelas</label>
                <select name="kelas_id" class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status Siswa</label>
                <select name="status" class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="aktif" {{ request('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif Belajar</option>
                    <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="mutasi_keluar" {{ request('status') == 'mutasi_keluar' ? 'selected' : '' }}>Mutasi Keluar</option>
                    <option value="dikeluarkan" {{ request('status') == 'dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
                    <option value="meninggal" {{ request('status') == 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                </select>
            </div>

            <!-- Tombol Filter & Tambah -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold rounded-lg transition">
                    Filter Data
                </button>
                <a href="{{ route('admin.siswa.create') }}" class="flex-1 py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg text-center transition flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Siswa
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Buku Induk Siswa -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase tracking-wider text-[11px] font-semibold">
                    <tr>
                        <th class="px-4 py-3.5">NISN / NIS</th>
                        <th class="px-4 py-3.5">Nama Lengkap Siswa</th>
                        <th class="px-4 py-3.5">L/P</th>
                        <th class="px-4 py-3.5">Kelas Aktif</th>
                        <th class="px-4 py-3.5">Wali Murid / No. HP</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-center">Aksi Buku Induk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($siswa as $s)
                        <tr class="hover:bg-emerald-50/40 transition">
                            <td class="px-4 py-3 font-mono font-medium">
                                <span class="text-gray-900 font-bold block">{{ $s->nisn }}</span>
                                <span class="text-gray-500 text-[11px]">NIS: {{ $s->nis }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold text-gray-800 block text-sm">{{ $s->nama_lengkap }}</span>
                                <span class="text-gray-500 text-[11px]">{{ $s->tempat_lahir }}, {{ $s->tanggal_lahir->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $s->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                    {{ $s->jenis_kelamin }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-emerald-800">
                                {{ $s->kelasAktif?->kelas?->nama_kelas ?? 'Belum ada kelas' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($s->wali->isNotEmpty())
                                    <span class="font-semibold text-gray-800 block">{{ $s->wali->first()->nama_lengkap }}</span>
                                    <span class="text-gray-500 text-[11px] font-mono">{{ $s->wali->first()->no_telepon }}</span>
                                @else
                                    <span class="text-gray-400 italic">Belum terhubung</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase {{ $s->status_siswa === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $s->status_siswa }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center space-x-1">
                                <a href="{{ route('admin.siswa.show', $s->id) }}" 
                                   title="Buka Buku Induk Lengkap"
                                   class="inline-flex items-center px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded border border-emerald-200 text-xs font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail
                                </a>
                                <a href="{{ route('admin.siswa.cetak-pdf', $s->id) }}" 
                                   target="_blank"
                                   title="Cetak Buku Induk PDF"
                                   class="inline-flex items-center px-2 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded border border-rose-200 text-xs font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    PDF
                                </a>
                                <a href="{{ route('admin.siswa.edit', $s->id) }}" 
                                   title="Edit Data"
                                   class="inline-flex items-center px-2 py-1 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded border border-gray-200 text-xs font-medium">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400 italic">
                                Belum ada data siswa yang cocok dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $siswa->links() }}
        </div>
    </div>
</x-dynamic-component>
