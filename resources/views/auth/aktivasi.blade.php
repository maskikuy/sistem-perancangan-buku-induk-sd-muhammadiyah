<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mb-3 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Aktivasi Akun Wali Murid</h2>
        <p class="text-xs text-gray-500 mt-1">Masukkan 10 digit NISN anak dan Kode Aktivasi yang diberikan oleh pihak sekolah</p>
    </div>

    <form method="POST" action="{{ route('wali.aktivasi.proses') }}">
        @csrf

        <!-- NISN Siswa -->
        <div>
            <label for="nisn" class="block font-medium text-sm text-gray-700">NISN Siswa (10 Digit)</label>
            <input id="nisn" 
                   type="text" 
                   name="nisn" 
                   value="{{ old('nisn') }}" 
                   maxlength="10" 
                   required 
                   autofocus 
                   placeholder="Contoh: 0191234567"
                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono" />
            <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
        </div>

        <!-- Kode Aktivasi -->
        <div class="mt-4">
            <label for="kode_aktivasi" class="block font-medium text-sm text-gray-700">Kode Aktivasi Sekolah</label>
            <input id="kode_aktivasi" 
                   type="text" 
                   name="kode_aktivasi" 
                   value="{{ old('kode_aktivasi') }}" 
                   required 
                   placeholder="Contoh: AKT-7788"
                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm uppercase font-mono tracking-wider" />
            <x-input-error :messages="$errors->get('kode_aktivasi')" class="mt-2" />
        </div>

        <!-- Password Baru -->
        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Kata Sandi Baru</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   placeholder="Minimal 6 karakter"
                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi Kata Sandi Baru</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   placeholder="Ketik ulang kata sandi"
                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" />
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a class="text-xs text-gray-600 hover:text-gray-900 underline" href="{{ route('login') }}">
                Kembali ke Halaman Login
            </a>

            <button type="submit" 
                    class="inline-flex items-center px-4 py-2.5 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition shadow-sm">
                Aktivasi & Masuk
            </button>
        </div>
    </form>
</x-guest-layout>
