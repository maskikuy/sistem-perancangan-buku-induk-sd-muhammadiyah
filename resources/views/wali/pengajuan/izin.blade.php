<x-dynamic-component :component="'layouts.wali-pwa'">
    <x-slot name="header">Formulir Izin Tidak Masuk Sekolah</x-slot>

    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-6">
        <div class="flex items-center space-x-2 pb-3 border-b border-gray-100 mb-4">
            <a href="{{ route('wali.pengajuan.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h3 class="font-bold text-sm text-gray-800">Pengajuan Izin: {{ $anak?->nama_lengkap }}</h3>
        </div>

        <form method="POST" action="{{ route('wali.pengajuan.izin.store') }}" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-medium text-gray-700 mb-1">Mulai Tanggal Izin</label>
                <input type="date" name="tanggal_mulai" value="{{ date('Y-m-d') }}" required
                       class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Sampai Tanggal (Opsional, jika > 1 hari)</label>
                <input type="date" name="tanggal_selesai" value="{{ date('Y-m-d') }}"
                       class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-1">Alasan Izin</label>
                <textarea name="alasan" rows="3" required placeholder="Contoh: Ananda sedang sakit demam dan disarankan istirahat oleh dokter..."
                          class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl text-[11px] text-amber-900 border border-amber-200">
                Pemberitahuan izin akan diteruskan kepada wali kelas ananda untuk diverifikasi dan otomatis tercatat pada presensi kelas.
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-sm transition active:scale-95">
                Kirim Pengajuan Izin
            </button>
        </form>
    </div>
</x-dynamic-component>
