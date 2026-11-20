<x-dynamic-component :component="'layouts.wali-pwa'">
    <x-slot name="header">Formulir Penjemputan Anak Pulang Cepat</x-slot>

    <div class="bg-white rounded-2xl p-4 border border-gray-200/80 shadow-sm mb-6">
        <div class="flex items-center space-x-2 pb-3 border-b border-gray-100 mb-4">
            <a href="{{ route('wali.pengajuan.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h3 class="font-bold text-sm text-gray-800">Penjemputan: {{ $anak?->nama_lengkap }}</h3>
        </div>

        <form method="POST" action="{{ route('wali.pengajuan.penjemputan.store') }}" class="space-y-4 text-xs">
            @csrf

            <!-- Tanggal Jemput -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Tanggal Penjemputan</label>
                <input type="date" name="tanggal_jemput" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required
                       class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono">
            </div>

            <!-- Jam Jemput -->
            <div x-data="{
                jamSekarang: '',
                init() {
                    const update = () => {
                        const now = new Date();
                        const h = String(now.getHours()).padStart(2, '0');
                        const m = String(now.getMinutes()).padStart(2, '0');
                        const s = String(now.getSeconds()).padStart(2, '0');
                        this.jamSekarang = `${h}:${m}:${s} WIB`;
                    };
                    update();
                    setInterval(update, 1000);
                }
            }">
                <div class="flex items-center justify-between mb-1">
                    <label class="font-medium text-gray-700">Rencana Jam Penjemputan di Gerbang</label>
                    <span class="text-[11px] font-mono text-gray-500">
                        Sekarang: <span x-text="jamSekarang" class="font-bold text-emerald-700"></span>
                    </span>
                </div>
                <input type="time" name="jam_jemput" required
                       class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono text-base font-bold text-purple-700">
                <span class="text-[10px] text-gray-500 mt-0.5 block">Minimal 30 menit dari sekarang kecuali kondisi mendesak.</span>
            </div>

            <!-- Opsi Mendesak Checkbox -->
            <div class="p-3 bg-rose-50 rounded-xl border border-rose-200 flex items-start space-x-2.5">
                <input id="is_mendesak" type="checkbox" name="is_mendesak" value="1"
                       class="rounded border-rose-300 text-rose-600 focus:ring-rose-500 mt-0.5">
                <label for="is_mendesak" class="text-xs text-rose-900 cursor-pointer">
                    <strong class="block font-bold">Kondisi Darurat / Mendesak</strong>
                    <span class="text-[11px] text-rose-700">Centang jika ananda harus segera dijemput tanpa menunggu batas 30 menit.</span>
                </label>
            </div>

            <!-- Siapa yang Menjemput -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="font-medium text-gray-700">Siapa yang Akan Menjemput?</label>
                    <a href="{{ route('wali.penjemput.index') }}" class="text-[11px] text-emerald-600 font-semibold underline">
                        + Kelola Penjemput
                    </a>
                </div>
                <select name="penjemput_alternatif_id" class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-semibold">
                    <option value="">Saya Sendiri (Orang Tua / Wali Murid Terdaftar)</option>
                    @foreach($daftarPenjemput as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama_lengkap }} ({{ $p->hubungan }}) - Telp: {{ $p->no_telepon }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Alasan Penjemputan -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Alasan Penjemputan Pulang Lebih Awal</label>
                <textarea name="alasan" rows="3" required placeholder="Contoh: Ada jadwal periksa ke dokter spesialis anak pukul 11.30 WIB..."
                          class="w-full text-xs rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>

            <div class="p-3 bg-emerald-50 rounded-xl text-[11px] text-emerald-900 border border-emerald-200">
                <strong>Alur Penjemputan:</strong> Setelah disetujui guru, petugas piket gerbang akan mencocokkan identitas penjemput dan menandai anak sudah dijemput. Presensi anak hari ini otomatis tercatat <em>"Pulang Lebih Awal"</em>.
            </div>

            <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-sm transition active:scale-95">
                Kirim Permohonan Penjemputan
            </button>
        </form>
    </div>
</x-dynamic-component>
