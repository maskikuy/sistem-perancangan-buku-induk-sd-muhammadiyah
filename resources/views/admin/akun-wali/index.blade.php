<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Kelola Akun Wali Murid & Kode Aktivasi</x-slot>

    <!-- Toolbar Pencarian -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.akun-wali.index') }}" class="w-full sm:w-80">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama wali / siswa / no hp..."
                   class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
        </form>
        <span class="text-xs text-gray-500">
            Wali murid mengaktivasi akun di: <code class="bg-gray-100 px-2 py-0.5 rounded text-emerald-700 font-mono">{{ url('/aktivasi-wali') }}</code>
        </span>
    </div>

    <!-- Tabel Akun Wali -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-[10px] font-semibold">
                    <tr>
                        <th class="px-4 py-3">Nama Wali Murid</th>
                        <th class="px-4 py-3">Siswa (Anak Kandung/Asuh)</th>
                        <th class="px-4 py-3">No. WhatsApp</th>
                        <th class="px-4 py-3">Username Login</th>
                        <th class="px-4 py-3 text-center">Kode Aktivasi</th>
                        <th class="px-4 py-3 text-center">Status Akun</th>
                        <th class="px-4 py-3 text-center">Aksi Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($wali as $w)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-gray-800">
                                {{ $w->nama_lengkap }}
                                <span class="text-[10px] text-gray-400 block font-normal">Hubungan: {{ ucfirst($w->hubungan) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @foreach($w->siswa as $anak)
                                    <span class="inline-block px-2 py-0.5 bg-emerald-50 border border-emerald-200 rounded text-[11px] font-medium text-emerald-800 mr-1 mb-1">
                                        {{ $anak->nama_lengkap }} (NISN: {{ $anak->nisn }})
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 font-mono">{{ $w->no_telepon }}</td>
                            <td class="px-4 py-3 font-mono font-semibold text-gray-800">
                                {{ $w->user?->username ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <code class="px-2 py-1 bg-gray-100 rounded border border-gray-200 font-mono font-bold text-emerald-700 text-xs tracking-wider">
                                    {{ $w->kode_aktivasi ?? 'SUDAH-AKTIF' }}
                                </code>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $w->is_teraktivasi ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $w->is_teraktivasi ? 'Aktif' : 'Menunggu Aktivasi' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center space-x-1 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.akun-wali.generate-kode', $w->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" title="Generate Ulang Kode" class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded border border-amber-200 text-[11px] font-medium">
                                        Kode Baru
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.akun-wali.reset-password', $w->id) }}" onsubmit="return confirm('Reset kata sandi wali ke: password123?')" class="inline">
                                    @csrf
                                    <button type="submit" title="Reset Kata Sandi" class="px-2 py-1 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded border border-gray-200 text-[11px] font-medium">
                                        Reset Sandi
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-400 italic">Belum ada akun wali terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $wali->links() }}
        </div>
    </div>
</x-dynamic-component>
