<x-dynamic-component :component="'layouts.wali-pwa'">
    <x-slot name="header">Profil Buku Induk Anak</x-slot>

    <!-- Header Profil -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4 text-center">
        <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-2xl mb-2 shadow-inner">
            {{ substr($anak->nama_lengkap, 0, 2) }}
        </div>
        <h3 class="text-base font-bold text-gray-800">{{ $anak->nama_lengkap }}</h3>
        <p class="text-xs text-gray-500 font-mono">NISN: {{ $anak->nisn }} &bull; NIS: {{ $anak->nis }}</p>

        <div class="mt-3 flex justify-center space-x-2">
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                {{ $anak->kelasAktif?->kelas?->nama_kelas ?? 'Kelas 1' }}
            </span>
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $anak->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                {{ $anak->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}
            </span>
        </div>
    </div>

    <!-- Rincian Buku Induk Siswa -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4 space-y-4 text-xs">
        <div>
            <h4 class="font-bold text-gray-800 pb-1.5 border-b border-gray-100 mb-2 flex items-center text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Identitas Diri
            </h4>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <div>
                    <span class="text-gray-400 block">Tempat, Tanggal Lahir:</span>
                    <span class="font-medium text-gray-800">{{ $anak->tempat_lahir }}, {{ $anak->tanggal_lahir->format('d/m/Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Agama:</span>
                    <span class="font-medium text-gray-800">{{ $anak->agama }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">NIK Siswa:</span>
                    <span class="font-mono text-gray-800">{{ $anak->nik ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Anak Ke / Saudara:</span>
                    <span class="font-medium text-gray-800">{{ $anak->anak_ke ?? 1 }} dari {{ $anak->jumlah_saudara ?? 1 }} bersaudara</span>
                </div>
            </div>
        </div>

        <div>
            <h4 class="font-bold text-gray-800 pb-1.5 border-b border-gray-100 mb-2 flex items-center text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Alamat Tempat Tinggal
            </h4>
            <p class="text-[11px] text-gray-700 font-medium leading-relaxed">
                {{ $anak->alamat_jalan }}, {{ $anak->desa_kelurahan }}, {{ $anak->kecamatan }}, {{ $anak->kabupaten_kota }}
            </p>
        </div>

        <div>
            <h4 class="font-bold text-gray-800 pb-1.5 border-b border-gray-100 mb-2 flex items-center text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Jasmani & Kesehatan
            </h4>
            <div class="grid grid-cols-3 gap-2 text-center text-[11px]">
                <div class="p-2 bg-gray-50 rounded-xl">
                    <span class="text-gray-400 block text-[10px]">Tinggi</span>
                    <span class="font-bold text-gray-800">{{ $anak->tinggi_badan_cm ? $anak->tinggi_badan_cm . ' cm' : '-' }}</span>
                </div>
                <div class="p-2 bg-gray-50 rounded-xl">
                    <span class="text-gray-400 block text-[10px]">Berat</span>
                    <span class="font-bold text-gray-800">{{ $anak->berat_badan_kg ? $anak->berat_badan_kg . ' kg' : '-' }}</span>
                </div>
                <div class="p-2 bg-gray-50 rounded-xl">
                    <span class="text-gray-400 block text-[10px]">Gol. Darah</span>
                    <span class="font-bold text-gray-800">{{ $anak->golongan_darah ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Permohonan Koreksi Data Buku Induk -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm" x-data="{ open: false }">
        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <h4 class="text-xs font-bold text-gray-800">Ada Kesalahan Data? Ajukan Koreksi</h4>
            </div>
            <span class="text-xs text-emerald-600 font-semibold" x-text="open ? 'Tutup' : 'Buka Form'"></span>
        </div>

        <form x-show="open" x-cloak method="POST" action="{{ route('wali.anak.koreksi') }}" class="mt-3 pt-3 border-t border-gray-100 text-xs space-y-3">
            @csrf
            <div>
                <label class="block font-medium text-gray-700 mb-1">Jelaskan data yang keliru dan perbaikannya:</label>
                <textarea name="koreksi" rows="3" required placeholder="Contoh: Tanggal lahir ananda yang benar adalah 15 Agustus 2020, di buku tertulis 16 Agustus..."
                          class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>
            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-sm transition active:scale-95">
                Kirim Permohonan Koreksi Data
            </button>
        </form>
    </div>
</x-dynamic-component>
