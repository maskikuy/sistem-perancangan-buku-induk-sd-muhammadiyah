<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Lembar Buku Induk Digital: {{ $siswa->nama_lengkap }}</x-slot>

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 border-b border-gray-200 mb-6 gap-3">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.siswa.index') }}" class="p-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $siswa->nama_lengkap }}</h3>
                <p class="text-xs text-gray-500 font-mono">NISN: {{ $siswa->nisn }} &bull; NIS: {{ $siswa->nis }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.siswa.cetak-pdf', $siswa->id) }}" target="_blank"
               class="inline-flex items-center px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Buku Induk PDF
            </a>
            <a href="{{ route('admin.siswa.edit', $siswa->id) }}" 
               class="inline-flex items-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Data
            </a>
        </div>
    </div>

    <!-- Layout Grid Kartu Buku Induk -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Ringkasan Foto & Status -->
        <div class="space-y-6">
            <!-- Profil Singkat -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm text-center">
                <div class="w-24 h-24 mx-auto rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-2xl mb-3 shadow-inner">
                    {{ substr($siswa->nama_lengkap, 0, 2) }}
                </div>
                <h4 class="font-bold text-base text-gray-800">{{ $siswa->nama_lengkap }}</h4>
                <p class="text-xs text-gray-500">Panggilan: "{{ $siswa->nama_panggilan ?? '-' }}"</p>
                <div class="mt-3 flex justify-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        {{ strtoupper($siswa->status_siswa) }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $siswa->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                        {{ $siswa->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                    </span>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 text-left text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kelas Aktif:</span>
                        <span class="font-bold text-gray-800">{{ $siswa->kelasAktif?->kelas?->nama_kelas ?? 'Belum ada' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal Masuk:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->tanggal_diterima->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diterima di Kelas:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->diterima_di_kelas }}</span>
                    </div>
                </div>
            </div>

            <!-- Kode Aktivasi Akun Wali Murid -->
            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl shadow-sm">
                <h5 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Aktivasi Portal Wali
                </h5>
                @if($siswa->wali->isNotEmpty())
                    @php $w = $siswa->wali->first(); @endphp
                    <div class="text-xs space-y-1">
                        <p class="text-gray-600">Wali: <strong class="text-gray-800">{{ $w->nama_lengkap }}</strong></p>
                        <p class="text-gray-600">Kode Aktivasi: <code class="bg-white px-2 py-0.5 rounded font-mono font-bold text-emerald-700 border border-emerald-300">{{ $w->kode_aktivasi ?? 'SUDAH-AKTIF' }}</code></p>
                        <p class="text-gray-600">Status Akun: 
                            <span class="font-semibold {{ $w->is_teraktivasi ? 'text-emerald-700' : 'text-amber-700' }}">
                                {{ $w->is_teraktivasi ? 'Sudah Diaktivasi' : 'Belum Diaktivasi' }}
                            </span>
                        </p>
                    </div>
                @else
                    <p class="text-xs text-gray-500 italic">Belum ada akun wali terhubung.</p>
                @endif
            </div>
        </div>

        <!-- Kolom Tengah & Kanan: Lembar Buku Induk Lengkap -->
        <div class="lg:col-span-2 space-y-6">
            <!-- 1. Identitas Lengkap Siswa -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    I. Data Diri & Identitas Siswa
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-4 text-xs">
                    <div>
                        <span class="text-gray-500 block">Nomor Induk Siswa Nasional (NISN):</span>
                        <span class="font-mono font-bold text-gray-800">{{ $siswa->nisn }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Nomor Induk Siswa (NIS Lokal):</span>
                        <span class="font-mono font-bold text-gray-800">{{ $siswa->nis }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Nomor Induk Kependudukan (NIK):</span>
                        <span class="font-mono text-gray-800">{{ $siswa->nik ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Tempat, Tanggal Lahir:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir->format('d F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Agama:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->agama }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Kewarganegaraan:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->kewarganegaraan }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Anak Ke / Dari:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->anak_ke ?? 1 }} dari {{ $siswa->jumlah_saudara ?? 1 }} bersaudara</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Status dalam Keluarga:</span>
                        <span class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $siswa->status_keluarga) }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. Alamat & Tempat Tinggal -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    II. Alamat & Tempat Tinggal
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-4 text-xs">
                    <div class="sm:col-span-2">
                        <span class="text-gray-500 block">Alamat Jalan / Dusun:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->alamat_jalan }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Desa / Kelurahan:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->desa_kelurahan ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Kapanewon / Kecamatan:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->kecamatan ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Kabupaten / Kota:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->kabupaten_kota }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Tinggal Bersama:</span>
                        <span class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $siswa->tinggal_bersama) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Jarak ke Sekolah:</span>
                        <span class="font-medium text-gray-800">{{ $siswa->jarak_ke_sekolah_km ? $siswa->jarak_ke_sekolah_km . ' km' : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Kondisi Fisik & Kesehatan -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    III. Jasmani & Kesehatan Siswa
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500 block">Tinggi Badan:</span>
                        <span class="text-sm font-bold text-gray-800">{{ $siswa->tinggi_badan_cm ? $siswa->tinggi_badan_cm . ' cm' : '-' }}</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500 block">Berat Badan:</span>
                        <span class="text-sm font-bold text-gray-800">{{ $siswa->berat_badan_kg ? $siswa->berat_badan_kg . ' kg' : '-' }}</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-500 block">Golongan Darah:</span>
                        <span class="text-sm font-bold text-gray-800">{{ $siswa->golongan_darah ?? 'Tidak Tahu' }}</span>
                    </div>
                </div>
                @if($siswa->riwayat_penyakit)
                    <div class="mt-3 text-xs">
                        <span class="text-gray-500 block">Riwayat Penyakit:</span>
                        <span class="text-gray-700 italic">{{ $siswa->riwayat_penyakit }}</span>
                    </div>
                @endif
            </div>

            <!-- 4. Orang Tua / Wali -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    IV. Data Orang Tua / Wali Murid
                </h4>
                @forelse($siswa->wali as $wali)
                    <div class="p-3 bg-gray-50 rounded-lg text-xs grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2">
                        <div>
                            <span class="text-gray-500 block">Nama Lengkap:</span>
                            <span class="font-bold text-gray-800">{{ $wali->nama_lengkap }} ({{ ucfirst($wali->pivot->hubungan) }})</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">No. Telepon / WhatsApp:</span>
                            <span class="font-mono text-gray-800 font-semibold">{{ $wali->no_telepon }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Pekerjaan:</span>
                            <span class="text-gray-700">{{ $wali->pekerjaan ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Pendidikan Terakhir:</span>
                            <span class="text-gray-700">{{ $wali->pendidikan_terakhir ?? '-' }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 italic">Belum ada data orang tua/wali tercatat.</p>
                @endforelse
            </div>

            <!-- 5. Riwayat Kelas & Kenaikan -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    V. Riwayat Penempatan Kelas
                </h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-600 font-semibold border-b">
                            <tr>
                                <th class="px-3 py-2">Tahun Ajaran</th>
                                <th class="px-3 py-2">Semester</th>
                                <th class="px-3 py-2">Kelas</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($siswa->riwayatKelas as $rk)
                                <tr>
                                    <td class="px-3 py-2 font-medium">{{ $rk->tahunAjaran->nama }}</td>
                                    <td class="px-3 py-2 capitalize">{{ $rk->semester->semester }}</td>
                                    <td class="px-3 py-2 font-bold text-emerald-800">{{ $rk->kelas->nama_kelas }}</td>
                                    <td class="px-3 py-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $rk->status_riwayat === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst(str_replace('_', ' ', $rk->status_riwayat)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-3 text-center text-gray-400 italic">Belum ada riwayat kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
