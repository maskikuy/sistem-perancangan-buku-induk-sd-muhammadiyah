<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Edit Buku Induk Siswa: {{ $siswa->nama_lengkap }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.siswa.update', $siswa->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Identitas Pokok Siswa -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-4 flex items-center">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2"></span>
                    I. Identitas Pokok Siswa
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">NISN <span class="text-rose-500">*</span></label>
                        <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" maxlength="10" required
                               class="w-full text-xs rounded-lg border-gray-300 font-mono focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">NIS Lokal <span class="text-rose-500">*</span></label>
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" required
                               class="w-full text-xs rounded-lg border-gray-300 font-mono focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-medium text-gray-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $siswa->nama_panggilan) }}"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" required
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir->format('Y-m-d')) }}" required
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Status Siswa</label>
                        <select name="status_siswa" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-semibold">
                            <option value="aktif" {{ old('status_siswa', $siswa->status_siswa) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ old('status_siswa', $siswa->status_siswa) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="mutasi_keluar" {{ old('status_siswa', $siswa->status_siswa) == 'mutasi_keluar' ? 'selected' : '' }}>Mutasi Keluar</option>
                            <option value="dikeluarkan" {{ old('status_siswa', $siswa->status_siswa) == 'dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
                            <option value="meninggal" {{ old('status_siswa', $siswa->status_siswa) == 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-medium text-gray-700 mb-1">Alamat Jalan</label>
                        <textarea name="alamat_jalan" rows="2" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">{{ old('alamat_jalan', $siswa->alamat_jalan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.siswa.show', $siswa->id) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                    Perbarui Data Siswa
                </button>
            </div>
        </form>
    </div>
</x-dynamic-component>
