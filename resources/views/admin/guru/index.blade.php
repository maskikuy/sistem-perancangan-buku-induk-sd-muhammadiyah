<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Kelola Data Guru & Tenaga Pendidik</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Guru Baru -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Tambah Guru & Buat Akun
            </h4>

            <form method="POST" action="{{ route('admin.guru.store') }}" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-gray-700 mb-1">NIP (Opsional)</label>
                    <input type="text" name="nip" placeholder="Contoh: 19800101..."
                           class="w-full text-xs rounded-lg border-gray-300 font-mono focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Nama Lengkap (Tanpa Gelar)</label>
                    <input type="text" name="nama_lengkap" required placeholder="Contoh: Ahmad Dahlan"
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Gelar Akademik</label>
                    <input type="text" name="gelar" placeholder="Contoh: S.Pd. / S.Pd.I."
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" placeholder="08xxxxxxxxxx"
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <p class="font-semibold text-emerald-800 mb-2">Akun Login Guru:</p>
                    <div class="space-y-2">
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Username Login</label>
                            <input type="text" name="username" required placeholder="Contoh: guru4"
                                   class="w-full text-xs rounded-lg border-gray-300 font-mono focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">Kata Sandi Awal</label>
                            <input type="password" name="password" required value="password123"
                                   class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <span class="text-[10px] text-gray-400">Default: password123</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition">
                    Simpan Guru & Akun
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Guru -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center justify-between">
                <span>Daftar Guru & Pendidik</span>
                <span class="text-xs text-gray-500 font-normal">Total: {{ $guru->count() }} Guru</span>
            </h4>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b text-gray-600 font-semibold uppercase text-[10px]">
                        <tr>
                            <th class="px-3 py-2.5">Nama & Gelar</th>
                            <th class="px-3 py-2.5">NIP</th>
                            <th class="px-3 py-2.5">Tugas Wali Kelas</th>
                            <th class="px-3 py-2.5">Username Login</th>
                            <th class="px-3 py-2.5">No. Telepon</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($guru as $g)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2.5">
                                    <span class="font-bold text-gray-800 block">{{ $g->nama_gelar }}</span>
                                    <span class="text-[11px] text-gray-400">{{ $g->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                                </td>
                                <td class="px-3 py-2.5 font-mono text-gray-600">{{ $g->nip ?? '-' }}</td>
                                <td class="px-3 py-2.5">
                                    @if($g->kelasWali)
                                        <span class="px-2 py-0.5 rounded font-semibold text-[10px] bg-emerald-100 text-emerald-800">
                                            Wali {{ $g->kelasWali->nama_kelas }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Guru Mata Pelajaran</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 font-mono text-emerald-700 font-bold">
                                    {{ $g->user?->username ?? '-' }}
                                </td>
                                <td class="px-3 py-2.5 font-mono text-gray-600">{{ $g->no_telepon ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dynamic-component>
