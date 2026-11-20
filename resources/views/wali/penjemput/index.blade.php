<x-dynamic-component :component="'layouts.wali-pwa'">
    <x-slot name="header">Daftar Penjemput Terpercaya</x-slot>

    <!-- Form Tambah Penjemput Alternatif -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-4" x-data="{ open: false }">
        <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
            <h4 class="text-xs font-bold text-gray-800 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Penjemput Alternatif Baru
            </h4>
            <span class="text-xs text-emerald-600 font-semibold" x-text="open ? 'Tutup' : '+ Buka Form'"></span>
        </div>

        <form x-show="open" x-cloak method="POST" action="{{ route('wali.penjemput.store') }}" class="mt-3 pt-3 border-t border-gray-100 text-xs space-y-3">
            @csrf
            <div>
                <label class="block font-medium text-gray-700 mb-1">Nama Lengkap Penjemput</label>
                <input type="text" name="nama_lengkap" required placeholder="Contoh: Joko Susilo"
                       class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Hubungan Keluarga</label>
                    <input type="text" name="hubungan" required placeholder="Paman / Kakek / Sopir"
                           class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">No. WhatsApp</label>
                    <input type="text" name="no_telepon" required placeholder="08xxxxxxxxxx"
                           class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">NIK (KTP, Opsional)</label>
                <input type="text" name="nik" placeholder="16 Digit NIK"
                       class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
            </div>

            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold shadow-sm transition active:scale-95">
                Simpan Penjemput Terpercaya
            </button>
        </form>
    </div>

    <!-- Daftar Penjemput yang Terdaftar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-6">
        <h4 class="text-xs font-bold text-gray-800 pb-2 border-b border-gray-100 mb-3">
            Penjemput Terdaftar
        </h4>

        <div class="space-y-3 text-xs">
            @forelse($daftarPenjemput as $p)
                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-gray-800 block text-xs">{{ $p->nama_lengkap }}</span>
                        <span class="text-[11px] text-emerald-700 font-semibold">Hubungan: {{ $p->hubungan }}</span>
                        <span class="text-[10px] text-gray-500 font-mono block">No. HP: {{ $p->no_telepon }}</span>
                    </div>
                    <form method="POST" action="{{ route('wali.penjemput.destroy', $p->id) }}" onsubmit="return confirm('Hapus data penjemput ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-rose-600 hover:text-rose-800 rounded hover:bg-rose-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-xs text-gray-400 py-4 text-center italic">Belum ada penjemput alternatif yang didaftarkan.</p>
            @endforelse
        </div>
    </div>
</x-dynamic-component>
