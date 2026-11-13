<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Induk - {{ $siswa->nama_lengkap }} ({{ $siswa->nisn }})</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #222;
            margin: 15mm 20mm;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 2px solid #059669;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop-surat h4 {
            margin: 0;
            font-size: 11pt;
            font-weight: normal;
            color: #333;
            letter-spacing: 0.5px;
        }
        .kop-surat h2 {
            margin: 2px 0;
            font-size: 15pt;
            font-weight: bold;
            color: #065f46;
        }
        .kop-surat p {
            margin: 0;
            font-size: 9pt;
            color: #555;
        }
        .judul-dokumen {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-dokumen h3 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            color: #111;
        }
        .judul-dokumen span {
            font-size: 9pt;
            color: #666;
        }
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            background-color: #f0fdf4;
            color: #065f46;
            padding: 4px 8px;
            border-left: 3px solid #059669;
            margin-top: 12px;
            margin-bottom: 6px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 8px;
        }
        table.data-table td {
            padding: 3.5px 5px;
            vertical-align: top;
        }
        table.data-table td.label {
            width: 32%;
            color: #444;
        }
        table.data-table td.colon {
            width: 3%;
            text-align: center;
        }
        table.data-table td.val {
            width: 65%;
            font-weight: 500;
        }
        table.border-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-top: 6px;
        }
        table.border-table th, table.border-table td {
            border: 1px solid #ccc;
            padding: 5px 8px;
        }
        table.border-table th {
            background-color: #f3f4f6;
            text-align: left;
            font-size: 8.5pt;
        }
        .ttd-box {
            margin-top: 25px;
            width: 100%;
        }
        .ttd-box td {
            text-align: center;
            font-size: 9.5pt;
            width: 50%;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT RESMI MUHAMMADIYAH -->
    <div class="kop-surat">
        <h4>MAJELIS PENDIDIKAN DASAR DAN MENENGAH PCM SLEMAN</h4>
        <h2>SD MUHAMMADIYAH SLEMAN</h2>
        <p>Jl. Magelang Km. 10, Tridadi, Sleman, Daerah Istimewa Yogyakarta 55514 &bull; Telp. (0274) 868xxx</p>
    </div>

    <!-- JUDUL DOKUMEN -->
    <div class="judul-dokumen">
        <h3>LEMBAR BUKU INDUK SISWA</h3>
        <span>Nomor Registrasi Induk: {{ $siswa->nis }} &bull; NISN: {{ $siswa->nisn }}</span>
    </div>

    <!-- I. IDENTITAS SISWA -->
    <div class="section-title">A. KETERANGAN IDENTITAS SISWA</div>
    <table class="data-table">
        <tr>
            <td class="label">1. Nama Lengkap</td>
            <td class="colon">:</td>
            <td class="val"><strong>{{ strtoupper($siswa->nama_lengkap) }}</strong></td>
        </tr>
        <tr>
            <td class="label">2. Nama Panggilan</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->nama_panggilan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">3. Jenis Kelamin</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="label">4. Tempat, Tanggal Lahir</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td class="label">5. Agama / Kewarganegaraan</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->agama }} / {{ $siswa->kewarganegaraan }}</td>
        </tr>
        <tr>
            <td class="label">6. Anak Ke / Bersaudara</td>
            <td class="colon">:</td>
            <td class="val">Anak ke-{{ $siswa->anak_ke ?? 1 }} dari {{ $siswa->jumlah_saudara ?? 1 }} bersaudara ({{ ucfirst(str_replace('_', ' ', $siswa->status_keluarga)) }})</td>
        </tr>
    </table>

    <!-- II. TEMPAT TINGGAL -->
    <div class="section-title">B. KETERANGAN TEMPAT TINGGAL</div>
    <table class="data-table">
        <tr>
            <td class="label">1. Alamat Jalan / Dusun</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->alamat_jalan }}</td>
        </tr>
        <tr>
            <td class="label">2. Kelurahan / Kapanewon</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->desa_kelurahan ?? '-' }} / {{ $siswa->kecamatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">3. Kabupaten / Kota</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->kabupaten_kota }}, {{ $siswa->provinsi }}</td>
        </tr>
        <tr>
            <td class="label">4. Tinggal Bersama / Jarak</td>
            <td class="colon">:</td>
            <td class="val">{{ ucfirst(str_replace('_', ' ', $siswa->tinggal_bersama)) }} / {{ $siswa->jarak_ke_sekolah_km ? $siswa->jarak_ke_sekolah_km . ' km' : '-' }}</td>
        </tr>
    </table>

    <!-- III. KESEHATAN -->
    <div class="section-title">C. KETERANGAN JASMANI & KESEHATAN</div>
    <table class="data-table">
        <tr>
            <td class="label">1. Berat / Tinggi Badan</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->berat_badan_kg ? $siswa->berat_badan_kg . ' kg' : '-' }} / {{ $siswa->tinggi_badan_cm ? $siswa->tinggi_badan_cm . ' cm' : '-' }}</td>
        </tr>
        <tr>
            <td class="label">2. Golongan Darah</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->golongan_darah ?? 'Tidak Tahu' }}</td>
        </tr>
        <tr>
            <td class="label">3. Riwayat Penyakit</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->riwayat_penyakit ?? 'Tidak ada catatan penyakit berat' }}</td>
        </tr>
    </table>

    <!-- IV. ORANG TUA / WALI -->
    <div class="section-title">D. KETERANGAN ORANG TUA / WALI MURID</div>
    <table class="data-table">
        @forelse($siswa->wali as $wali)
            <tr>
                <td class="label">Nama {{ ucfirst($wali->pivot->hubungan) }}</td>
                <td class="colon">:</td>
                <td class="val"><strong>{{ $wali->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td class="label">Pekerjaan / Kontak</td>
                <td class="colon">:</td>
                <td class="val">{{ $wali->pekerjaan ?? '-' }} &bull; Telp/WA: {{ $wali->no_telepon }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" style="font-style: italic; color: #777;">Data wali belum terdata lengkap</td>
            </tr>
        @endforelse
    </table>

    <!-- V. PENERIMAAN DI SEKOLAH -->
    <div class="section-title">E. PENERIMAAN DI SEKOLAH INI</div>
    <table class="data-table">
        <tr>
            <td class="label">1. Tanggal Diterima</td>
            <td class="colon">:</td>
            <td class="val">{{ $siswa->tanggal_diterima->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td class="label">2. Diterima di Tingkat/Kelas</td>
            <td class="colon">:</td>
            <td class="val">Kelas {{ $siswa->diterima_di_kelas }}</td>
        </tr>
        <tr>
            <td class="label">3. Status Siswa Saat Ini</td>
            <td class="colon">:</td>
            <td class="val"><strong>{{ strtoupper($siswa->status_siswa) }}</strong></td>
        </tr>
    </table>

    <!-- TANDA TANGAN RESMI -->
    <table class="ttd-box">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala SD Muhammadiyah Sleman<br><br><br><br>
                <strong>Drs. H. Sudirman, M.Pd.</strong><br>
                NIP. 196805121994031002
            </td>
            <td>
                Sleman, {{ date('d F Y') }}<br>
                Petugas Buku Induk (Tata Usaha)<br><br><br><br>
                <strong>{{ auth()->user()->name ?? 'Staf Tata Usaha' }}</strong><br>
                NIP. 198904012015011005
            </td>
        </tr>
    </table>
</body>
</html>
