<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Tambah Siswa Baru ke Buku Induk</x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.siswa.store') }}" class="space-y-6">
            @csrf

            <!-- Section 1: Identitas Pokok Siswa -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-4 flex items-center">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2"></span>
                    I. Identitas Pokok Siswa
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">NISN (10 Digit) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" maxlength="10" required placeholder="Contoh: 0191234567"
                               class="w-full text-xs rounded-lg border-gray-300 font-mono focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('nisn')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">NIS Lokal Sekolah <span class="text-rose-500">*</span></label>
                        <input type="text" name="nis" value="{{ old('nis') }}" required placeholder="Contoh: 26005"
                               class="w-full text-xs rounded-lg border-gray-300 font-mono focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('nis')" class="mt-1" />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-medium text-gray-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Sesuai Akta Kelahiran"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" placeholder="Nama akrab ananda"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', 'Sleman') }}" required
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Agama</label>
                        <input type="text" name="agama" value="{{ old('agama', 'Islam') }}" required
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Ditempatkan di Rombel Kelas <span class="text-rose-500">*</span></label>
                        <select name="kelas_id" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            @foreach($daftarKelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Tanggal Diterima Sekolah <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', date('Y-m-d')) }}" required
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Section 2: Alamat & Tempat Tinggal -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-4 flex items-center">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2"></span>
                    II. Alamat & Tempat Tinggal
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="sm:col-span-2">
                        <label class="block font-medium text-gray-700 mb-1">Alamat Jalan / Dusun / RT / RW <span class="text-rose-500">*</span></label>
                        <textarea name="alamat_jalan" rows="2" required placeholder="Jl. ..., RT ... / RW ..."
                                  class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">{{ old('alamat_jalan') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Desa / Kelurahan</label>
                        <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan') }}" placeholder="Contoh: Triharjo"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Kapanewon / Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', 'Sleman') }}"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Kabupaten / Kota</label>
                        <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota', 'Sleman') }}"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Jarak ke Sekolah (km)</label>
                        <input type="number" step="0.1" name="jarak_ke_sekolah_km" value="{{ old('jarak_ke_sekolah_km') }}" placeholder="Contoh: 2.5"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Data Orang Tua / Wali & Akun Login -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-4 flex items-center">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2"></span>
                    III. Data Orang Tua / Wali & Akun Portal
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Nama Orang Tua / Wali <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali') }}" required placeholder="Nama lengkap wali murid"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Hubungan Keluarga <span class="text-rose-500">*</span></label>
                        <select name="hubungan_wali" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="ayah">Ayah Kandung</option>
                            <option value="ibu">Ibu Kandung</option>
                            <option value="wali">Wali / Pengasuh</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_telepon_wali" value="{{ old('no_telepon_wali') }}" required placeholder="08xxxxxxxxxx"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan_wali" value="{{ old('pekerjaan_wali') }}" placeholder="Contoh: Karyawan / PNS / Wiraswasta"
                               class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <div class="mt-4 p-3 bg-emerald-50 rounded-lg border border-emerald-100 text-xs text-emerald-800">
                    <p class="font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Otomatisasi Akun Portal Wali Murid
                    </p>
                    <p class="mt-0.5 text-emerald-700">
                        Sistem akan otomatis menghasilkan Akun User untuk wali murid menggunakan Username: <strong class="font-mono">NISN Siswa</strong> dan Kode Aktivasi acak untuk dibagikan ke wali murid.
                    </p>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.siswa.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                    Simpan ke Buku Induk
                </button>
            </div>
        </form>
    </div>
</x-dynamic-component>
