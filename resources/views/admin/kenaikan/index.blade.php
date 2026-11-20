<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Proses Kenaikan Kelas & Kelulusan Massal</x-slot>

    <!-- Toolbar Kelas Asal -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.kenaikan.index') }}" class="flex items-center space-x-2">
            <label class="text-xs font-semibold text-gray-700">Pilih Kelas Asal:</label>
            <select name="kelas_asal_id" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-semibold text-emerald-800">
                @foreach($daftarKelas as $k)
                    <option value="{{ $k->id }}" {{ $k->id == $kelasAsalId ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                    </option>
                @endforeach
            </select>
        </form>

        <span class="text-xs text-gray-500">
            Tahun Ajaran Aktif: <strong class="text-emerald-700">{{ $taAktif?->nama ?? '-' }}</strong>
        </span>
    </div>

    <!-- Form Kenaikan Kelas Massal -->
    <form method="POST" action="{{ route('admin.kenaikan.proses') }}" onsubmit="return confirm('Jalankan proses kenaikan/kelulusan untuk siswa yang dicentang?')">
        @csrf
        <input type="hidden" name="kelas_asal_id" value="{{ $kelasAsalId }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Daftar Siswa dengan Checkbox -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                    <h4 class="font-bold text-sm text-gray-800">Daftar Siswa di Kelas Ini</h4>
                    <button type="button" onclick="document.querySelectorAll('input.siswa-check').forEach(el => el.checked = true)"
                            class="text-xs text-emerald-600 font-semibold hover:underline">
                        ✓ Centang Semua
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] font-semibold border-b">
                            <tr>
                                <th class="px-3 py-2 w-8 text-center">Pilih</th>
                                <th class="px-3 py-2">NISN / Nama Siswa</th>
                                <th class="px-3 py-2 text-center">Status Rekomendasi Rapor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($daftarSiswa as $s)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2.5 text-center">
                                        <input type="checkbox" name="siswa_id[]" value="{{ $s->id }}" class="siswa-check rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    </td>
                                    <td class="px-3 py-2.5">
                                        <span class="font-bold text-gray-800 block text-xs">{{ $s->nama_lengkap }}</span>
                                        <span class="text-[11px] text-gray-400 font-mono">NISN: {{ $s->nisn }}</span>
                                    </td>
                                    <td class="px-3 py-2.5 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Siap Naik Kelas
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-gray-400 italic">Tidak ada siswa aktif di kelas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Panel Pengaturan Tujuan -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit" x-data="{ aksi: 'naik_kelas' }">
                <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3">
                    Pengaturan Kenaikan / Kelulusan
                </h4>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Tindakan</label>
                        <select name="aksi" x-model="aksi" required class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-semibold">
                            <option value="naik_kelas">Naik ke Tingkat / Rombel Berikutnya</option>
                            <option value="lulus">Lulus Sekolah (Khusus Tingkat 6)</option>
                        </select>
                    </div>

                    <div x-show="aksi === 'naik_kelas'" x-cloak>
                        <label class="block font-medium text-gray-700 mb-1">Pilih Kelas Tujuan</label>
                        <select name="kelas_tujuan_id" class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                            @foreach($daftarKelas as $kt)
                                <option value="{{ $kt->id }}">{{ $kt->nama_kelas }} (Tingkat {{ $kt->tingkat }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 text-amber-900 text-[11px]">
                        <strong>Perhatian:</strong> Siswa yang dicentang akan otomatis dipindahkan ke riwayat kelas tujuan dan rekam jejak lamanya tetap tersimpan utuh di Buku Induk Digital.
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-sm transition active:scale-95">
                        Jalankan Proses
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-dynamic-component>
