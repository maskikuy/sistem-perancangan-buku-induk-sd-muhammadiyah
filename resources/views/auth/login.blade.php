<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mb-3 shadow-inner">
            <!-- Icon Sekolah / Buku Induk -->
            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Sistem Informasi Buku Induk</h2>
        <p class="text-xs font-medium text-emerald-700 tracking-wide mt-1">SD MUHAMMADIYAH SLEMAN</p>
        <p class="text-xs text-gray-500 mt-1">Silakan masuk menggunakan akun terdaftar</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username / Email / NIP -->
        <div>
            <label for="login" class="block font-medium text-sm text-gray-700">Username / Email / NIP</label>
            <input id="login" 
                   type="text" 
                   name="login" 
                   value="{{ old('login') }}" 
                   required 
                   autofocus 
                   placeholder="Contoh: admin / guru1 / wali1"
                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ show: false }">
            <label for="password" class="block font-medium text-sm text-gray-700">Kata Sandi</label>
            <div class="relative mt-1">
                <input id="password" 
                       :type="show ? 'text' : 'password'" 
                       name="password" 
                       required 
                       placeholder="••••••••"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm pr-10" />
                <button type="button" 
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Ingat Saya & Lupa Sandi -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-xs text-gray-600">Ingat Saya</span>
            </label>

            <a class="text-xs text-emerald-600 hover:text-emerald-800 underline" href="{{ route('wali.aktivasi') }}">
                Aktivasi Akun Wali
            </a>
        </div>

        <div class="mt-5">
            <button type="submit" 
                    class="w-full justify-center inline-flex items-center px-4 py-2.5 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                Masuk ke Sistem
            </button>
        </div>
    </form>

    <!-- Bantuan Akun Contoh (Demo Sidang Skripsi) -->
    <div class="mt-6 pt-4 border-t border-gray-200">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 text-center">Akun Uji Coba Demo Sidang</p>
        <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <button type="button" 
                    onclick="document.getElementById('login').value='admin'; document.getElementById('password').value='password123';"
                    class="p-2 bg-gray-50 hover:bg-emerald-50 rounded border border-gray-200 hover:border-emerald-300 transition">
                <span class="block font-bold text-gray-700">Admin/TU</span>
                <span class="text-gray-400 text-[10px]">admin</span>
            </button>
            <button type="button" 
                    onclick="document.getElementById('login').value='guru1'; document.getElementById('password').value='password123';"
                    class="p-2 bg-gray-50 hover:bg-emerald-50 rounded border border-gray-200 hover:border-emerald-300 transition">
                <span class="block font-bold text-gray-700">Guru Kelas 1</span>
                <span class="text-gray-400 text-[10px]">guru1</span>
            </button>
            <button type="button" 
                    onclick="document.getElementById('login').value='wali1'; document.getElementById('password').value='password123';"
                    class="p-2 bg-gray-50 hover:bg-emerald-50 rounded border border-gray-200 hover:border-emerald-300 transition">
                <span class="block font-bold text-gray-700">Wali Murid</span>
                <span class="text-gray-400 text-[10px]">wali1</span>
            </button>
        </div>
        <p class="text-[11px] text-gray-400 text-center mt-2">Sandi semua akun demo: <code class="bg-gray-100 px-1 py-0.5 rounded text-gray-600 font-mono">password123</code></p>
    </div>
</x-guest-layout>
