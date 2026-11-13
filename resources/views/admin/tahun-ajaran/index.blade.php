<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Kelola Tahun Ajaran & Semester Aktif</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Tahun Ajaran Baru -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm h-fit">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-3 flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                Tambah Tahun Ajaran
            </h4>
            <form method="POST" action="{{ route('admin.tahun-ajaran.store') }}" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Nama Tahun Ajaran</label>
                    <input type="text" name="nama" required placeholder="Contoh: 2027/2028"
                           class="w-full text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    <x-input-error :messages="$errors->get('nama')" class="mt-1" />
                </div>
                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition">
                    Simpan Tahun Ajaran
                </button>
            </form>
        </div>

        <!-- Daftar Tahun Ajaran & Semester -->
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm lg:col-span-2">
            <h4 class="font-bold text-sm text-gray-800 pb-2 border-b border-gray-100 mb-4 flex items-center justify-between">
                <span>Daftar Periode Akademik</span>
                <span class="text-xs text-gray-500 font-normal">Klik tombol "Aktifkan" untuk mengatur periode berjalan</span>
            </h4>

            <div class="space-y-4">
                @foreach($tahunAjaran as $ta)
                    <div class="p-4 rounded-xl border {{ $ta->is_aktif ? 'border-emerald-500 bg-emerald-50/30' : 'border-gray-200 bg-white' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <h5 class="text-base font-bold text-gray-800">{{ $ta->nama }}</h5>
                                @if($ta->is_aktif)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                                        Tahun Ajaran Aktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- 2 Semester -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            @foreach($ta->semester as $sem)
                                <div class="p-3 rounded-lg border {{ $sem->is_aktif ? 'border-emerald-400 bg-white shadow-sm' : 'border-gray-200 bg-gray-50' }} flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-gray-800 capitalize">Semester {{ $sem->semester }}</p>
                                        <p class="text-[11px] {{ $sem->is_aktif ? 'text-emerald-600 font-semibold' : 'text-gray-400' }}">
                                            {{ $sem->is_aktif ? 'Aktif Saat Ini' : 'Tidak Aktif' }}
                                        </p>
                                    </div>
                                    @if(!$sem->is_aktif)
                                        <form method="POST" action="{{ route('admin.tahun-ajaran.set-aktif', [$ta->id, $sem->id]) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-300 rounded text-xs font-semibold shadow-sm transition">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @else
                                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                            ✓
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-dynamic-component>
