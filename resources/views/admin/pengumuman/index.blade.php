<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Kelola Pengumuman Sekolah</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Pengumuman -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit" x-data="{ sasaran: 'semua' }">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Terbitkan Pengumuman Baru
            </h4>

            <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Judul Pengumuman</label>
                    <input type="text" name="judul" required placeholder="Contoh: Pemberitahuan PTS Ganjil"
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Target / Sasaran</label>
                    <select name="sasaran" x-model="sasaran" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-semibold">
                        <option value="semua">Semua (Guru & Wali Murid)</option>
                        <option value="wali_semua">Seluruh Wali Murid</option>
                        <option value="guru">Hanya Dewan Guru</option>
                        <option value="per_kelas">Wali Murid Per Kelas Tertentu</option>
                    </select>
                </div>

                <div x-show="sasaran === 'per_kelas'" x-cloak>
                    <label class="block font-medium text-gray-700 mb-1">Pilih Kelas Sasaran</label>
                    <select name="kelas_id" class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                    <textarea name="isi" rows="4" required placeholder="Tuliskan isi maklumat lengkap di sini..."
                              class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition">
                    Siarkan Pengumuman
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Pengumuman -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3">
                Daftar Pengumuman Aktif
            </h4>

            <div class="space-y-3">
                @forelse($pengumuman as $p)
                    <div class="p-4 rounded-xl border border-gray-200 bg-white hover:border-emerald-200 transition text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                Sasaran: {{ str_replace('_', ' ', $p->sasaran) }} {{ $p->kelas ? '(' . $p->kelas->nama_kelas . ')' : '' }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $p->tanggal_terbit->format('d/m/Y H:i') }}</span>
                        </div>
                        <h5 class="text-sm font-bold text-gray-800 mb-1">{{ $p->judul }}</h5>
                        <p class="text-gray-600 leading-relaxed mb-3">{{ $p->isi }}</p>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-[11px] text-gray-500">
                            <span>Oleh: <strong>{{ $p->pembuat?->name ?? 'Admin' }}</strong></span>
                            <form method="POST" action="{{ route('admin.pengumuman.destroy', $p->id) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 py-6 text-center italic">Belum ada pengumuman yang diterbitkan.</p>
                @endforelse
            </div>

            <div class="p-3 border-t border-gray-200 mt-4">
                {{ $pengumuman->links() }}
            </div>
        </div>
    </div>
</x-dynamic-component>
