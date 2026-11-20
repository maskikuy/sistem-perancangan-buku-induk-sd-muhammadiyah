@props([
    'variant' => 'badge', // 'badge', 'minimal', 'card', 'piket'
    'showDate' => true,
    'showSeconds' => true,
])

<div x-data="{
    hari: '',
    tanggal: '',
    jam: '',
    update() {
        const now = new Date();
        const hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const bulanSingkat = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        this.hari = hariList[now.getDay()];
        const d = String(now.getDate()).padStart(2, '0');
        const m = bulanList[now.getMonth()];
        const mShort = bulanSingkat[now.getMonth()];
        const y = now.getFullYear();

        this.tanggalLengkap = `${this.hari}, ${d} ${m} ${y}`;
        this.tanggalSingkat = `${this.hari}, ${d} ${mShort} ${y}`;

        const hh = String(now.getHours()).padStart(2, '0');
        const mm = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');
        this.jam = `${hh}:${mm}:${ss} WIB`;
        this.jamSingkat = `${hh}:${mm} WIB`;
    },
    init() {
        this.update();
        setInterval(() => this.update(), 1000);
    }
}" {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>

    @if($variant === 'badge')
        <!-- Desain Badge Elegan untuk Header Topbar (Admin / Guru) -->
        <div class="inline-flex items-center space-x-2 bg-emerald-50/80 border border-emerald-200/90 text-emerald-900 px-3 py-1 rounded-lg text-xs font-mono shadow-sm">
            <svg class="w-3.5 h-3.5 text-emerald-600 animate-pulse flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            @if($showDate)
                <span x-text="tanggalSingkat" class="font-medium text-emerald-800 hidden sm:inline"></span>
                <span class="text-emerald-300 hidden sm:inline">&bull;</span>
            @endif
            <span x-text="jam" class="font-bold text-emerald-900 tracking-wider"></span>
        </div>

    @elseif($variant === 'minimal')
        <!-- Desain Minimalis Teks untuk Mobile PWA Wali Murid -->
        <div class="flex items-center space-x-1.5 text-xs text-gray-600 font-mono">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            @if($showDate)
                <span x-text="tanggalSingkat" class="font-medium text-gray-600"></span>
                <span class="text-gray-300">|</span>
            @endif
            <span x-text="jam" class="font-bold text-emerald-700"></span>
        </div>

    @elseif($variant === 'piket')
        <!-- Desain Khusus Pos Piket Gerbang (Menonjol & Jelas) -->
        <div class="bg-amber-600 text-white px-3.5 py-1.5 rounded-lg flex items-center space-x-2.5 font-mono shadow-inner border border-amber-400">
            <svg class="w-4 h-4 text-amber-200 animate-pulse flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-right">
                <span x-text="tanggalLengkap" class="text-[11px] block text-amber-100 font-medium"></span>
                <span x-text="jam" class="text-sm font-bold text-white tracking-widest block leading-none mt-0.5"></span>
            </div>
        </div>

    @elseif($variant === 'pwa-card')
        <!-- Desain Khusus Kartu Kehadiran PWA Mobile Wali Murid -->
        <div class="text-right font-mono">
            <span x-text="tanggalLengkap" class="text-[10px] text-gray-500 block font-sans"></span>
            <span x-text="jam" class="text-xs font-bold text-emerald-700 block tracking-wide"></span>
        </div>

    @endif
</div>
