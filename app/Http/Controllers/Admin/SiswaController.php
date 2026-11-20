<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\Wali;
use App\Models\WaliSiswa;
use App\Services\AuditTrailService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar Buku Induk Siswa dengan filter kelas, status, dan pencarian
     */
    public function index(Request $request)
    {
        $query = Siswa::with(['kelasAktif.kelas', 'wali']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_siswa', $request->status);
        } else {
            $query->where('status_siswa', 'aktif');
        }

        // Filter kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('riwayatKelas', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id)->where('status_riwayat', 'aktif');
            });
        }

        // Pencarian nama, NISN, atau NIS
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('nisn', 'like', "%{$cari}%")
                  ->orWhere('nis', 'like', "%{$cari}%");
            });
        }

        $siswa = $query->orderBy('nama_lengkap', 'asc')->paginate(15)->withQueryString();
        $daftarKelas = Kelas::orderBy('tingkat', 'asc')->get();

        return view('admin.siswa.index', compact('siswa', 'daftarKelas'));
    }

    /**
     * Form tambah siswa baru ke Buku Induk
     */
    public function create()
    {
        $daftarKelas = Kelas::orderBy('tingkat', 'asc')->get();
        return view('admin.siswa.create', compact('daftarKelas'));
    }

    /**
     * Menyimpan data siswa baru ke database buku induk beserta data orang tua/wali
     */
    public function store(Request $request)
    {
        $request->validate([
            'nisn'             => ['required', 'string', 'size:10', 'unique:siswa,nisn'],
            'nis'              => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'nama_lengkap'     => ['required', 'string', 'max:100'],
            'jenis_kelamin'    => ['required', 'in:L,P'],
            'tempat_lahir'     => ['required', 'string', 'max:60'],
            'tanggal_lahir'    => ['required', 'date'],
            'agama'            => ['required', 'string'],
            'alamat_jalan'     => ['required', 'string'],
            'tanggal_diterima' => ['required', 'date'],
            'kelas_id'         => ['required', 'exists:kelas,id'],
            'nama_wali'        => ['required', 'string', 'max:100'],
            'hubungan_wali'    => ['required', 'in:ayah,ibu,wali'],
            'no_telepon_wali'  => ['required', 'string', 'max:20'],
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan data siswa
            $siswa = Siswa::create([
                'nisn'                => $request->nisn,
                'nis'                 => $request->nis,
                'nama_lengkap'        => $request->nama_lengkap,
                'nama_panggilan'      => $request->nama_panggilan,
                'nik'                 => $request->nik,
                'jenis_kelamin'       => $request->jenis_kelamin,
                'tempat_lahir'        => $request->tempat_lahir,
                'tanggal_lahir'       => $request->tanggal_lahir,
                'agama'               => $request->agama,
                'kewarganegaraan'     => $request->kewarganegaraan ?? 'WNI',
                'anak_ke'             => $request->anak_ke,
                'jumlah_saudara'      => $request->jumlah_saudara,
                'status_keluarga'     => $request->status_keluarga ?? 'anak_kandung',
                'alamat_jalan'        => $request->alamat_jalan,
                'desa_kelurahan'      => $request->desa_kelurahan,
                'kecamatan'           => $request->kecamatan,
                'kabupaten_kota'      => $request->kabupaten_kota ?? 'Sleman',
                'provinsi'            => $request->provinsi ?? 'D.I. Yogyakarta',
                'kode_pos'            => $request->kode_pos,
                'tinggal_bersama'     => $request->tinggal_bersama ?? 'orang_tua',
                'jarak_ke_sekolah_km' => $request->jarak_ke_sekolah_km,
                'berat_badan_kg'      => $request->berat_badan_kg,
                'tinggi_badan_cm'     => $request->tinggi_badan_cm,
                'golongan_darah'      => $request->golongan_darah ?? 'tidak_tahu',
                'riwayat_penyakit'    => $request->riwayat_penyakit,
                'tanggal_diterima'    => $request->tanggal_diterima,
                'diterima_di_kelas'   => $request->diterima_di_kelas ?? '1',
                'status_siswa'        => 'aktif',
            ]);

            // 2. Buat akun wali & data wali
            $kodeAktivasi = 'AKT-' . strtoupper(Str::random(4));
            $userWali = User::create([
                'name'       => $request->nama_wali,
                'username'   => $request->nisn, // Login default menggunakan NISN anak
                'email'      => null,
                'no_hp'      => $request->no_telepon_wali,
                'role_aktif' => 'wali',
                'password'   => Hash::make('password123'),
                'is_aktif'   => true,
            ]);
            $userWali->assignRole('wali');

            $wali = Wali::create([
                'user_id'             => $userWali->id,
                'nik'                 => $request->nik_wali,
                'nama_lengkap'        => $request->nama_wali,
                'hubungan'            => $request->hubungan_wali,
                'pendidikan_terakhir' => $request->pendidikan_wali,
                'pekerjaan'           => $request->pekerjaan_wali,
                'penghasilan_bulanan' => $request->penghasilan_wali,
                'no_telepon'          => $request->no_telepon_wali,
                'alamat'              => $request->alamat_jalan,
                'kode_aktivasi'       => $kodeAktivasi,
                'is_teraktivasi'      => false,
            ]);

            // Hubungkan di pivot
            WaliSiswa::create([
                'wali_id'         => $wali->id,
                'siswa_id'        => $siswa->id,
                'hubungan'        => $request->hubungan_wali,
                'is_kontak_utama' => true,
            ]);

            // 3. Masukkan ke riwayat kelas aktif
            $taAktif  = TahunAjaran::where('is_aktif', true)->first();
            $semAktif = Semester::where('is_aktif', true)->first();

            RiwayatKelas::create([
                'siswa_id'        => $siswa->id,
                'kelas_id'        => $request->kelas_id,
                'tahun_ajaran_id' => $taAktif ? $taAktif->id : 1,
                'semester_id'     => $semAktif ? $semAktif->id : 1,
                'status_riwayat'  => 'aktif',
            ]);

            // Catat di audit trail
            AuditTrailService::catat('CREATE', 'siswa', $siswa->id, null, ['nama' => $siswa->nama_lengkap, 'nisn' => $siswa->nisn]);

            DB::commit();
            return redirect()->route('admin.siswa.show', $siswa->id)->with('success', 'Data siswa berhasil didaftarkan ke Buku Induk Digital!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan lembar Buku Induk Digital lengkap satu siswa
     */
    public function show($id)
    {
        $siswa = Siswa::with([
            'wali',
            'riwayatKelas.kelas',
            'riwayatKelas.tahunAjaran',
            'riwayatKelas.semester',
            'mutasi',
            'nilai.mataPelajaran'
        ])->findOrFail($id);

        // Ambil 10 presensi terakhir tanpa SQL window function agar kompatibel dengan seluruh versi MySQL/MariaDB
        $siswa->setRelation('presensi', $siswa->presensi()->latest('tanggal')->take(10)->get());

        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Form edit data buku induk siswa
     */
    public function edit($id)
    {
        $siswa = Siswa::with('wali')->findOrFail($id);
        $daftarKelas = Kelas::orderBy('tingkat', 'asc')->get();

        return view('admin.siswa.edit', compact('siswa', 'daftarKelas'));
    }

    /**
     * Update data siswa di buku induk
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nisn'          => ['required', 'string', 'size:10', 'unique:siswa,nisn,' . $siswa->id],
            'nis'           => ['required', 'string', 'max:20', 'unique:siswa,nis,' . $siswa->id],
            'nama_lengkap'  => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir'  => ['required', 'string', 'max:60'],
            'tanggal_lahir' => ['required', 'date'],
            'status_siswa'  => ['required', 'in:aktif,lulus,mutasi_keluar,dikeluarkan,meninggal'],
        ]);

        $dataLama = $siswa->toArray();
        $siswa->update($request->all());

        AuditTrailService::catat('UPDATE', 'siswa', $siswa->id, $dataLama, $siswa->fresh()->toArray());

        return redirect()->route('admin.siswa.show', $siswa->id)->with('success', 'Data buku induk siswa berhasil diperbarui!');
    }

    /**
     * Cetak Lembar Buku Induk Siswa dalam format PDF resmi
     */
    public function cetakPdf($id)
    {
        $siswa = Siswa::with([
            'wali',
            'riwayatKelas.kelas',
            'riwayatKelas.tahunAjaran',
            'mutasi'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.buku-induk', compact('siswa'))
                  ->setPaper('a4', 'portrait');

        AuditTrailService::catat('CETAK_PDF', 'siswa', $siswa->id, null, ['jenis' => 'Buku Induk PDF']);

        return $pdf->stream("Buku_Induk_{$siswa->nisn}_{$siswa->nama_lengkap}.pdf");
    }
}
