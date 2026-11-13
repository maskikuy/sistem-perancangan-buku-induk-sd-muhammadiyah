<x-dynamic-component :component="'layouts.admin'">
    <x-slot name="header">Audit Trail Log Aktivitas Sistem</x-slot>

    <!-- Toolbar Filter -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex items-center space-x-2 w-full sm:w-auto">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari user, tabel, atau IP..."
                   class="text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 w-60">
            <select name="aksi" class="text-xs rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Semua Aksi</option>
                <option value="LOGIN" {{ request('aksi') === 'LOGIN' ? 'selected' : '' }}>LOGIN</option>
                <option value="CREATE" {{ request('aksi') === 'CREATE' ? 'selected' : '' }}>CREATE</option>
                <option value="UPDATE" {{ request('aksi') === 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                <option value="DELETE" {{ request('aksi') === 'DELETE' ? 'selected' : '' }}>DELETE</option>
                <option value="APPROVE" {{ request('aksi') === 'APPROVE' ? 'selected' : '' }}>APPROVE</option>
                <option value="REJECT" {{ request('aksi') === 'REJECT' ? 'selected' : '' }}>REJECT</option>
            </select>
            <button type="submit" class="py-2 px-3 bg-gray-800 hover:bg-gray-900 text-white text-xs font-semibold rounded-lg transition">
                Filter
            </button>
        </form>

        <span class="text-xs text-gray-500">
            Total Rekaman Audit: <strong class="text-gray-800">{{ $logs->total() }}</strong> aktivitas
        </span>
    </div>

    <!-- Tabel Audit Trail -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-gray-50 border-b text-gray-600 uppercase text-[10px] font-semibold">
                    <tr>
                        <th class="px-4 py-3">Waktu Kejadian</th>
                        <th class="px-4 py-3">Pengguna (User)</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Tabel Terkait / ID</th>
                        <th class="px-4 py-3">IP Address & User Agent</th>
                        <th class="px-4 py-3">Perubahan Data (JSON)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-mono">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 text-[11px]">
                            <td class="px-4 py-2.5 text-gray-600 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-2.5 font-sans font-semibold text-gray-800">
                                {{ $log->user?->name ?? 'Sistem Otomatis' }}
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $log->aksi === 'LOGIN' ? 'bg-blue-100 text-blue-700' : ($log->aksi === 'CREATE' ? 'bg-emerald-100 text-emerald-700' : ($log->aksi === 'DELETE' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700')) }}">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-gray-700">
                                {{ $log->tabel_terkait }} {{ $log->record_id ? '#' . $log->record_id : '' }}
                            </td>
                            <td class="px-4 py-2.5 text-[10px] text-gray-500">
                                <span class="block text-gray-700 font-semibold">{{ $log->ip_address ?? '-' }}</span>
                                <span class="truncate block max-w-xs font-sans">{{ $log->user_agent }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-[10px] max-w-xs truncate">
                                @if($log->data_baru)
                                    <span class="text-emerald-700">{{ json_encode($log->data_baru) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400 font-sans italic">Belum ada catatan log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    </div>
</x-dynamic-component>
