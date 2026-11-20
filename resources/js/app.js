import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('realtimeClock', () => ({
    hari: '',
    tanggalSingkat: '',
    tanggalLengkap: '',
    jam: '',
    jamSingkat: '',
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
}));

Alpine.start();
