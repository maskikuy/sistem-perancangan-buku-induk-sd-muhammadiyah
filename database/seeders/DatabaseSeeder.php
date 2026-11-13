<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\CatatanWaliKelas;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\MutasiSiswa;
use App\Models\Nilai;
use App\Models\Notifikasi;
use App\Models\Pengajuan;
use App\Models\Pengumuman;
use App\Models\PenjemputAlternatif;
use App\Models\Presensi;
use App\Models\RiwayatKelas;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\Wali;
use App\Models\WaliSiswa;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Menjalankan seed data awal sistem buku induk sekolah
     */
    public function run(): void
    {
        // 1. Buat Roles via Spatie
        $roleAdmin  = Role::firstOrCreate(['name' => 'admin']);
        $roleGuru   = Role::firstOrCreate(['name' => 'guru']);
        $roleWali   = Role::firstOrCreate(['name' => 'wali']);
        $roleKepsek = Role::firstOrCreate(['name' => 'kepsek']);

        // 2. Buat Akun Pengguna Inti
        // Admin / TU
        $userAdmin = User::create([
            'name'       => 'Staf Tata Usaha SD',
            'username'   => 'admin',
            'email'      => 'tu@sdmuhsleman.sch.id',
            'no_hp'      => '081223344556',
            'role_aktif' => 'admin',
            'password'   => Hash::make('password123'),
            'is_aktif'   => true,
        ]);
        $userAdmin->assignRole($roleAdmin);

        // Kepala Sekolah
        $userKepsek = User::create([
            'name'       => 'Drs. H. Sudirman, M.Pd.',
            'username'   => 'kepsek',
            'email'      => 'kepsek@sdmuhsleman.sch.id',
            'no_hp'      => '081334455667',
            'role_aktif' => 'kepsek',
            'password'   => Hash::make('password123'),
            'is_aktif'   => true,
        ]);
        $userKepsek->assignRole($roleKepsek);

        // Guru 1 (Wali Kelas 1)
        $userGuru1 = User::create([
            'name'       => 'Ahmad Dahlan, S.Pd.',
            'username'   => 'guru1',
            'email'      => 'ahmad.dahlan@sdmuhsleman.sch.id',
            'no_hp'      => '081445566778',
            'role_aktif' => 'guru',
            'password'   => Hash::make('password123'),
            'is_aktif'   => true,
        ]);
        $userGuru1->assignRole($roleGuru);

        $guru1 = Guru::create([
            'user_id'       => $userGuru1->id,
            'nip'           => '198001012005011001',
            'nama_lengkap'  => 'Ahmad Dahlan',
            'gelar'         => 'S.Pd.',
            'jenis_kelamin' => 'L',
            'no_telepon'    => '081445566778',
            'alamat'        => 'Jl. Magelang Km. 10, Tridadi, Sleman',
            'is_wali_kelas' => true,
        ]);

        // Guru 2 (Wali Kelas 2)
        $userGuru2 = User::create([
            'name'       => 'Siti Walidah, S.Pd.I.',
            'username'   => 'guru2',
            'email'      => 'siti.walidah@sdmuhsleman.sch.id',
            'no_hp'      => '081556677889',
            'role_aktif' => 'guru',
            'password'   => Hash::make('password123'),
            'is_aktif'   => true,
        ]);
        $userGuru2->assignRole($roleGuru);

        $guru2 = Guru::create([
            'user_id'       => $userGuru2->id,
            'nip'           => '198502022008012002',
            'nama_lengkap'  => 'Siti Walidah',
            'gelar'         => 'S.Pd.I.',
            'jenis_kelamin' => 'P',
            'no_telepon'    => '081556677889',
            'alamat'        => 'Jl. Gito Gati, Sleman',
            'is_wali_kelas' => true,
        ]);

        // Guru 3 (Guru Mapel Ismuba / PAI)
        $userGuru3 = User::create([
            'name'       => 'H. Fachrodin, M.Pd.I.',
            'username'   => 'guru3',
            'email'      => 'fachrodin@sdmuhsleman.sch.id',
            'no_hp'      => '081667788990',
            'role_aktif' => 'guru',
            'password'   => Hash::make('password123'),
            'is_aktif'   => true,
        ]);
        $userGuru3->assignRole($roleGuru);

        $guru3 = Guru::create([
            'user_id'       => $userGuru3->id,
            'nip'           => '199003032015011003',
            'nama_lengkap'  => 'H. Fachrodin',
            'gelar'         => 'M.Pd.I.',
            'jenis_kelamin' => 'L',
            'no_telepon'    => '081667788990',
            'alamat'        => 'Mlati, Sleman',
            'is_wali_kelas' => false,
        ]);

        // Wali Murid 1 (Multi-anak: punya 2 anak di sekolah ini)
        $userWali1 = User::create([
            'name'       => 'Budi Santoso, S.T.',
            'username'   => 'wali1',
            'email'      => 'budi.santoso@gmail.com',
            'no_hp'      => '081778899001',
            'role_aktif' => 'wali',
            'password'   => Hash::make('password123'),
            'is_aktif'   => true,
        ]);
        $userWali1->assignRole($roleWali);

        $wali1 = Wali::create([
            'user_id'             => $userWali1->id,
            'nik'                 => '3404011205800001',
            'nama_lengkap'        => 'Budi Santoso, S.T.',
            'hubungan'            => 'ayah',
            'pendidikan_terakhir' => 'S1 Teknik Elektro',
            'pekerjaan'           => 'Karyawan Swasta',
            'penghasilan_bulanan' => 'Rp 7.000.000 - Rp 10.000.000',
            'no_telepon'          => '081778899001',
            'alamat'              => 'Perum Sleman Permai II No. 14, Triharjo, Sleman',
            'kode_aktivasi'       => 'AKT-7788',
            'is_teraktivasi'      => true,
        ]);

        // Wali Murid 2
        $userWali2 = User::create([
            'name'       => 'Anisa Rahmawati, S.E.',
            'username'   => 'wali2',
            'email'      => 'anisa.rahma@gmail.com',
            'no_hp'      => '081889900112',
            'role_aktif' => 'wali',
            'password'   => Hash::make('password123'),
            'is_aktif'   => true,
        ]);
        $userWali2->assignRole($roleWali);

        $wali2 = Wali::create([
            'user_id'             => $userWali2->id,
            'nik'                 => '3404024508850002',
            'nama_lengkap'        => 'Anisa Rahmawati, S.E.',
            'hubungan'            => 'ibu',
            'pendidikan_terakhir' => 'S1 Akuntansi',
            'pekerjaan'           => 'Wiraswasta',
            'penghasilan_bulanan' => 'Rp 5.000.000 - Rp 7.000.000',
            'no_telepon'          => '081889900112',
            'alamat'              => 'Jl. Kaliurang Km 9, Ngaglik, Sleman',
            'kode_aktivasi'       => 'AKT-8899',
            'is_teraktivasi'      => true,
        ]);

        // 3. Tahun Ajaran & Semester
        $ta = TahunAjaran::create([
            'nama'     => '2026/2027',
            'is_aktif' => true,
        ]);

        $semGanjil = Semester::create([
            'tahun_ajaran_id' => $ta->id,
            'semester'        => 'ganjil',
            'is_aktif'        => true,
            'tanggal_mulai'   => '2026-07-15',
            'tanggal_selesai' => '2026-12-20',
        ]);

        $semGenap = Semester::create([
            'tahun_ajaran_id' => $ta->id,
            'semester'        => 'genap',
            'is_aktif'        => false,
            'tanggal_mulai'   => '2027-01-05',
            'tanggal_selesai' => '2027-06-25',
        ]);

        // 4. Data Kelas
        $kelas1 = Kelas::create([
            'tahun_ajaran_id' => $ta->id,
            'guru_id'         => $guru1->id,
            'nama_kelas'      => '1 Abu Bakar Ash-Shiddiq',
            'tingkat'         => 1,
            'kapasitas'       => 28,
        ]);

        $kelas2 = Kelas::create([
            'tahun_ajaran_id' => $ta->id,
            'guru_id'         => $guru2->id,
            'nama_kelas'      => '2 Umar Bin Khattab',
            'tingkat'         => 2,
            'kapasitas'       => 28,
        ]);

        // 5. Mata Pelajaran (Umum & Ciri Khusus Muhammadiyah)
        $mapelPAI = MataPelajaran::create([
            'kode_mapel' => 'PAI-01',
            'nama_mapel' => 'Pendidikan Agama Islam',
            'kelompok'   => 'umum',
            'kkm'        => 75.00,
        ]);

        $mapelKMD = MataPelajaran::create([
            'kode_mapel' => 'KMD-01',
            'nama_mapel' => 'Kemuhammadiyahan',
            'kelompok'   => 'ciri_khusus_muhammadiyah',
            'kkm'        => 75.00,
        ]);

        $mapelBAR = MataPelajaran::create([
            'kode_mapel' => 'BAR-01',
            'nama_mapel' => 'Bahasa Arab',
            'kelompok'   => 'ciri_khusus_muhammadiyah',
            'kkm'        => 75.00,
        ]);

        $mapelMTK = MataPelajaran::create([
            'kode_mapel' => 'MTK-01',
            'nama_mapel' => 'Matematika',
            'kelompok'   => 'umum',
            'kkm'        => 72.00,
        ]);

        $mapelBIN = MataPelajaran::create([
            'kode_mapel' => 'BIN-01',
            'nama_mapel' => 'Bahasa Indonesia',
            'kelompok'   => 'umum',
            'kkm'        => 75.00,
        ]);

        $mapelIPAS = MataPelajaran::create([
            'kode_mapel' => 'IPAS-01',
            'nama_mapel' => 'Ilmu Pengetahuan Alam dan Sosial (IPAS)',
            'kelompok'   => 'umum',
            'kkm'        => 75.00,
        ]);

        $mapelBJW = MataPelajaran::create([
            'kode_mapel' => 'BJW-01',
            'nama_mapel' => 'Bahasa Jawa (Muatan Lokal DIY)',
            'kelompok'   => 'muatan_lokal',
            'kkm'        => 70.00,
        ]);

        // 6. Data Siswa (Buku Induk Digital)
        // Anak 1 Budi Santoso (Kelas 1)
        $siswaFarhan = Siswa::create([
            'nisn'                => '0191234567',
            'nis'                 => '26001',
            'nama_lengkap'        => 'Muhammad Farhan Al-Fatih',
            'nama_panggilan'      => 'Farhan',
            'nik'                 => '3404011508200001',
            'jenis_kelamin'       => 'L',
            'tempat_lahir'        => 'Sleman',
            'tanggal_lahir'       => '2020-08-15',
            'agama'               => 'Islam',
            'kewarganegaraan'     => 'WNI',
            'anak_ke'             => 2,
            'jumlah_saudara'      => 1,
            'status_keluarga'     => 'anak_kandung',
            'alamat_jalan'        => 'Perum Sleman Permai II No. 14, RT 02 / RW 05',
            'desa_kelurahan'      => 'Triharjo',
            'kecamatan'           => 'Sleman',
            'kabupaten_kota'      => 'Sleman',
            'provinsi'            => 'D.I. Yogyakarta',
            'kode_pos'            => '55514',
            'tinggal_bersama'     => 'orang_tua',
            'jarak_ke_sekolah_km' => 2.5,
            'berat_badan_kg'      => 21.5,
            'tinggi_badan_cm'     => 118.0,
            'golongan_darah'      => 'O',
            'riwayat_penyakit'    => 'Tidak ada riwayat penyakit berat',
            'foto'                => null,
            'tanggal_diterima'    => '2026-07-15',
            'diterima_di_kelas'   => '1',
            'status_siswa'        => 'aktif',
        ]);

        // Anak 2 Budi Santoso (Kakak Farhan, Kelas 2) -> Multi-anak
        $siswaAisyah = Siswa::create([
            'nisn'                => '0181234568',
            'nis'                 => '25012',
            'nama_lengkap'        => 'Aisyah Ayudia Inara',
            'nama_panggilan'      => 'Aisyah',
            'nik'                 => '3404015002190002',
            'jenis_kelamin'       => 'P',
            'tempat_lahir'        => 'Yogyakarta',
            'tanggal_lahir'       => '2019-02-10',
            'agama'               => 'Islam',
            'kewarganegaraan'     => 'WNI',
            'anak_ke'             => 1,
            'jumlah_saudara'      => 1,
            'status_keluarga'     => 'anak_kandung',
            'alamat_jalan'        => 'Perum Sleman Permai II No. 14, RT 02 / RW 05',
            'desa_kelurahan'      => 'Triharjo',
            'kecamatan'           => 'Sleman',
            'kabupaten_kota'      => 'Sleman',
            'provinsi'            => 'D.I. Yogyakarta',
            'kode_pos'            => '55514',
            'tinggal_bersama'     => 'orang_tua',
            'jarak_ke_sekolah_km' => 2.5,
            'berat_badan_kg'      => 24.0,
            'tinggi_badan_cm'     => 124.5,
            'golongan_darah'      => 'A',
            'riwayat_penyakit'    => 'Alergi debu ringan',
            'foto'                => null,
            'tanggal_diterima'    => '2025-07-14',
            'diterima_di_kelas'   => '1',
            'status_siswa'        => 'aktif',
        ]);

        // Anak dari Bu Anisa (Kelas 1)
        $siswaRayyan = Siswa::create([
            'nisn'                => '0199876543',
            'nis'                 => '26002',
            'nama_lengkap'        => 'Rayyan Danendra Putra',
            'nama_panggilan'      => 'Rayyan',
            'nik'                 => '3404021203200003',
            'jenis_kelamin'       => 'L',
            'tempat_lahir'        => 'Sleman',
            'tanggal_lahir'       => '2020-03-12',
            'agama'               => 'Islam',
            'kewarganegaraan'     => 'WNI',
            'anak_ke'             => 1,
            'jumlah_saudara'      => 0,
            'status_keluarga'     => 'anak_kandung',
            'alamat_jalan'        => 'Jl. Kaliurang Km 9, Sinduharjo',
            'desa_kelurahan'      => 'Sinduharjo',
            'kecamatan'           => 'Ngaglik',
            'kabupaten_kota'      => 'Sleman',
            'provinsi'            => 'D.I. Yogyakarta',
            'kode_pos'            => '55581',
            'tinggal_bersama'     => 'orang_tua',
            'jarak_ke_sekolah_km' => 4.0,
            'berat_badan_kg'      => 22.0,
            'tinggi_badan_cm'     => 120.0,
            'golongan_darah'      => 'B',
            'riwayat_penyakit'    => null,
            'foto'                => null,
            'tanggal_diterima'    => '2026-07-15',
            'diterima_di_kelas'   => '1',
            'status_siswa'        => 'aktif',
        ]);

        // Siswa 4 (Kelas 1)
        $siswaZahra = Siswa::create([
            'nisn'                => '0195566778',
            'nis'                 => '26003',
            'nama_lengkap'        => 'Zahra Putri Khairunnisa',
            'nama_panggilan'      => 'Zahra',
            'nik'                 => '3404016609200004',
            'jenis_kelamin'       => 'P',
            'tempat_lahir'        => 'Sleman',
            'tanggal_lahir'       => '2020-09-26',
            'agama'               => 'Islam',
            'kewarganegaraan'     => 'WNI',
            'anak_ke'             => 1,
            'jumlah_saudara'      => 2,
            'status_keluarga'     => 'anak_kandung',
            'alamat_jalan'        => 'Murangan, Triharjo',
            'desa_kelurahan'      => 'Triharjo',
            'kecamatan'           => 'Sleman',
            'kabupaten_kota'      => 'Sleman',
            'provinsi'            => 'D.I. Yogyakarta',
            'kode_pos'            => '55514',
            'tinggal_bersama'     => 'orang_tua',
            'jarak_ke_sekolah_km' => 1.5,
            'berat_badan_kg'      => 20.0,
            'tinggi_badan_cm'     => 117.0,
            'golongan_darah'      => 'AB',
            'riwayat_penyakit'    => null,
            'foto'                => null,
            'tanggal_diterima'    => '2026-07-15',
            'diterima_di_kelas'   => '1',
            'status_siswa'        => 'aktif',
        ]);

        // 7. Relasi Wali Murid ke Siswa (Pivot: Many-to-Many Multi-Anak)
        WaliSiswa::create([
            'wali_id'         => $wali1->id,
            'siswa_id'        => $siswaFarhan->id,
            'hubungan'        => 'ayah',
            'is_kontak_utama' => true,
        ]);

        WaliSiswa::create([
            'wali_id'         => $wali1->id,
            'siswa_id'        => $siswaAisyah->id,
            'hubungan'        => 'ayah',
            'is_kontak_utama' => true,
        ]);

        WaliSiswa::create([
            'wali_id'         => $wali2->id,
            'siswa_id'        => $siswaRayyan->id,
            'hubungan'        => 'ibu',
            'is_kontak_utama' => true,
        ]);

        // 8. Penjemput Alternatif
        $penjemputPakde = PenjemputAlternatif::create([
            'wali_id'        => $wali1->id,
            'siswa_id'       => null, // Berlaku untuk semua anak dari Pak Budi
            'nama_lengkap'   => 'Joko Susilo',
            'nik'            => '3404012001750005',
            'hubungan'       => 'Paman / Pakde',
            'no_telepon'     => '081234567890',
            'foto_identitas' => null,
            'is_aktif'       => true,
        ]);

        // 9. Riwayat Kelas Siswa
        RiwayatKelas::create([
            'siswa_id'        => $siswaFarhan->id,
            'kelas_id'        => $kelas1->id,
            'tahun_ajaran_id' => $ta->id,
            'semester_id'     => $semGanjil->id,
            'status_riwayat'  => 'aktif',
        ]);

        RiwayatKelas::create([
            'siswa_id'        => $siswaRayyan->id,
            'kelas_id'        => $kelas1->id,
            'tahun_ajaran_id' => $ta->id,
            'semester_id'     => $semGanjil->id,
            'status_riwayat'  => 'aktif',
        ]);

        RiwayatKelas::create([
            'siswa_id'        => $siswaZahra->id,
            'kelas_id'        => $kelas1->id,
            'tahun_ajaran_id' => $ta->id,
            'semester_id'     => $semGanjil->id,
            'status_riwayat'  => 'aktif',
        ]);

        RiwayatKelas::create([
            'siswa_id'        => $siswaAisyah->id,
            'kelas_id'        => $kelas2->id,
            'tahun_ajaran_id' => $ta->id,
            'semester_id'     => $semGanjil->id,
            'status_riwayat'  => 'aktif',
        ]);

        // 10. Jadwal Pelajaran Kelas 1 Abu Bakar
        Jadwal::create([
            'kelas_id'          => $kelas1->id,
            'mata_pelajaran_id' => $mapelPAI->id,
            'guru_id'           => $guru3->id,
            'semester_id'       => $semGanjil->id,
            'hari'              => 'senin',
            'jam_mulai'         => '07:30:00',
            'jam_selesai'       => '09:00:00',
        ]);

        Jadwal::create([
            'kelas_id'          => $kelas1->id,
            'mata_pelajaran_id' => $mapelKMD->id,
            'guru_id'           => $guru1->id,
            'semester_id'       => $semGanjil->id,
            'hari'              => 'senin',
            'jam_mulai'         => '09:15:00',
            'jam_selesai'       => '10:45:00',
        ]);

        Jadwal::create([
            'kelas_id'          => $kelas1->id,
            'mata_pelajaran_id' => $mapelMTK->id,
            'guru_id'           => $guru1->id,
            'semester_id'       => $semGanjil->id,
            'hari'              => 'selasa',
            'jam_mulai'         => '07:30:00',
            'jam_selesai'       => '09:00:00',
        ]);

        Jadwal::create([
            'kelas_id'          => $kelas1->id,
            'mata_pelajaran_id' => $mapelBIN->id,
            'guru_id'           => $guru1->id,
            'semester_id'       => $semGanjil->id,
            'hari'              => 'rabu',
            'jam_mulai'         => '07:30:00',
            'jam_selesai'       => '09:00:00',
        ]);

        Jadwal::create([
            'kelas_id'          => $kelas1->id,
            'mata_pelajaran_id' => $mapelBAR->id,
            'guru_id'           => $guru3->id,
            'semester_id'       => $semGanjil->id,
            'hari'              => 'kamis',
            'jam_mulai'         => '07:30:00',
            'jam_selesai'       => '09:00:00',
        ]);

        Jadwal::create([
            'kelas_id'          => $kelas1->id,
            'mata_pelajaran_id' => $mapelIPAS->id,
            'guru_id'           => $guru1->id,
            'semester_id'       => $semGanjil->id,
            'hari'              => 'jumat',
            'jam_mulai'         => '07:30:00',
            'jam_selesai'       => '09:00:00',
        ]);

        // 11. Presensi Contoh Hari Ini & Kemarin
        $tglHariIni = Carbon::today()->toDateString();
        $tglKemarin = Carbon::yesterday()->toDateString();

        // Farhan hadir
        Presensi::create([
            'siswa_id'    => $siswaFarhan->id,
            'kelas_id'    => $kelas1->id,
            'semester_id' => $semGanjil->id,
            'tanggal'     => $tglKemarin,
            'status'      => 'hadir',
            'jam_masuk'   => '06:55:00',
            'jam_pulang'  => '13:00:00',
            'keterangan'  => 'Tepat waktu',
            'guru_id'     => $guru1->id,
        ]);

        Presensi::create([
            'siswa_id'    => $siswaFarhan->id,
            'kelas_id'    => $kelas1->id,
            'semester_id' => $semGanjil->id,
            'tanggal'     => $tglHariIni,
            'status'      => 'hadir',
            'jam_masuk'   => '06:50:00',
            'jam_pulang'  => null,
            'keterangan'  => 'Tepat waktu',
            'guru_id'     => $guru1->id,
        ]);

        // Rayyan izin kemarin
        Presensi::create([
            'siswa_id'    => $siswaRayyan->id,
            'kelas_id'    => $kelas1->id,
            'semester_id' => $semGanjil->id,
            'tanggal'     => $tglKemarin,
            'status'      => 'izin',
            'jam_masuk'   => null,
            'jam_pulang'  => null,
            'keterangan'  => 'Acara keluarga',
            'guru_id'     => $guru1->id,
        ]);

        // 12. Nilai Contoh Rapor Farhan
        Nilai::create([
            'siswa_id'           => $siswaFarhan->id,
            'kelas_id'           => $kelas1->id,
            'mata_pelajaran_id'  => $mapelPAI->id,
            'semester_id'        => $semGanjil->id,
            'guru_id'            => $guru3->id,
            'jenis_nilai'        => 'pts',
            'nilai_angka'        => 88.50,
            'capaian_kompetensi' => 'Sangat baik dalam menghafal surat pendek juz 30 dan adab shalat.',
        ]);

        Nilai::create([
            'siswa_id'           => $siswaFarhan->id,
            'kelas_id'           => $kelas1->id,
            'mata_pelajaran_id'  => $mapelKMD->id,
            'semester_id'        => $semGanjil->id,
            'guru_id'            => $guru1->id,
            'jenis_nilai'        => 'pts',
            'nilai_angka'        => 90.00,
            'capaian_kompetensi' => 'Memahami dengan sangat baik lambang dan sejarah berdirinya Muhammadiyah.',
        ]);

        Nilai::create([
            'siswa_id'           => $siswaFarhan->id,
            'kelas_id'           => $kelas1->id,
            'mata_pelajaran_id'  => $mapelMTK->id,
            'semester_id'        => $semGanjil->id,
            'guru_id'            => $guru1->id,
            'jenis_nilai'        => 'pts',
            'nilai_angka'        => 85.00,
            'capaian_kompetensi' => 'Mampu melakukan penjumlahan dan pengurangan bilangan cacah 1-20.',
        ]);

        // 13. Catatan Wali Kelas
        CatatanWaliKelas::create([
            'siswa_id'        => $siswaFarhan->id,
            'kelas_id'        => $kelas1->id,
            'semester_id'     => $semGanjil->id,
            'guru_id'         => $guru1->id,
            'catatan'         => 'Ananda Farhan menunjukkan perkembangan akhlak dan kemandirian yang sangat baik. Pertahankan semangat belajarnya!',
            'status_kenaikan' => 'belum_ditentukan',
        ]);

        // 14. Pengumuman Sekolah
        Pengumuman::create([
            'judul'           => 'Milad Muhammadiyah ke-114: Agenda Jalan Sehat dan Lomba Siswa',
            'isi'             => 'Dalam rangka menyemarakkan Milad Muhammadiyah ke-114, SD Muhammadiyah Sleman menyelenggarakan Jalan Sehat Keluarga dan Lomba Mewarnai Kaligrafi pada hari Sabtu pekan depan.',
            'sasaran'         => 'semua',
            'pembuat_user_id' => $userAdmin->id,
            'tanggal_terbit'  => now(),
            'lampiran'        => null,
        ]);

        Pengumuman::create([
            'judul'           => 'Jadwal Penilaian Tengah Semester (PTS) Ganjil TA 2026/2027',
            'isi'             => 'Diberitahukan kepada seluruh wali murid bahwa pelaksanaan PTS Ganjil akan dimulai tanggal 22 September 2026. Mohon bimbingan belajar ananda di rumah.',
            'sasaran'         => 'wali_semua',
            'pembuat_user_id' => $userAdmin->id,
            'tanggal_terbit'  => now()->subDays(2),
            'lampiran'        => null,
        ]);

        // 15. Contoh Pengajuan Izin & Penjemputan untuk Simulasi Demo
        // Pengajuan Penjemputan Hari Ini (Status: Menunggu)
        $pengajuanJemput = Pengajuan::create([
            'nomor_pengajuan'         => 'AJU-' . date('Ymd') . '-0001',
            'siswa_id'                => $siswaFarhan->id,
            'wali_id'                 => $wali1->id,
            'jenis'                   => 'penjemputan',
            'tanggal_mulai'           => Carbon::today(),
            'jam_jemput'              => '11:30:00',
            'is_mendesak'             => false,
            'penjemput_alternatif_id' => $penjemputPakde->id,
            'alasan'                  => 'Ada jadwal kontrol dokter spesialis anak di RS Sardjito',
            'bukti_dokumen'           => null,
            'status'                  => 'menunggu',
        ]);

        // Notifikasi ke guru wali kelas
        Notifikasi::create([
            'user_id' => $userGuru1->id,
            'judul'   => 'Pengajuan Penjemputan Baru',
            'pesan'   => 'Wali dari ' . $siswaFarhan->nama_lengkap . ' mengajukan penjemputan pada jam 11:30.',
            'tipe'    => 'pengajuan_baru',
            'tautan'  => '/guru/pengajuan',
        ]);

        // Audit Log Awal
        AuditLog::create([
            'user_id'       => $userAdmin->id,
            'aksi'          => 'CREATE',
            'tabel_terkait' => 'master_data',
            'record_id'     => 1,
            'data_lama'     => null,
            'data_baru'     => ['pesan' => 'Inisialisasi data master sekolah berhasil dilakukan.'],
            'ip_address'    => '127.0.0.1',
            'user_agent'    => 'DatabaseSeeder/CLI',
            'created_at'    => now(),
        ]);
    }
}
