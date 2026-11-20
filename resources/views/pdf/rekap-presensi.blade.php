<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Siswa - {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #222; margin: 10mm 15mm; }
        .kop { text-align: center; border-bottom: 2px solid #059669; padding-bottom: 6px; margin-bottom: 12px; }
        .kop h3 { margin: 0; font-size: 11pt; color: #333; }
        .kop h2 { margin: 2px 0; font-size: 14pt; color: #065f46; font-weight: bold; }
        .kop p { margin: 0; font-size: 8.5pt; color: #555; }
        .title { text-align: center; margin-bottom: 12px; }
        .title h4 { margin: 0; font-size: 11pt; text-decoration: underline; }
        table.data { width: 100%; border-collapse: collapse; font-size: 8.5pt; }
        table.data th, table.data td { border: 1px solid #999; padding: 4px 6px; }
        table.data th { background-color: #f0fdf4; color: #065f46; text-align: left; }
    </style>
</head>
<body>
    <div class="kop">
        <h3>MAJELIS PENDIDIKAN DASAR DAN MENENGAH PCM SLEMAN</h3>
        <h2>SD MUHAMMADIYAH SLEMAN</h2>
        <p>Jl. Magelang Km. 10, Tridadi, Sleman, D.I. Yogyakarta 55514 &bull; Telp. (0274) 868xxx</p>
    </div>

    <div class="title">
        <h4>REKAPITULASI PRESENSI KEHADIRAN SISWA</h4>
        <span style="font-size: 9pt;">Kelas: {{ $kelas->nama_kelas }} &bull; Periode: {{ Carbon\Carbon::create(2026, $bulan, 1)->isoFormat('MMMM Y') }} &bull; Wali Kelas: {{ $kelas->waliKelas?->nama_gelar ?? '-' }}</span>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Lengkap Siswa</th>
                <th style="width: 15%; text-align: center;">Status</th>
                <th style="width: 15%;">Jam Masuk/Pulang</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $idx => $p)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                    <td><strong>{{ $p->siswa->nama_lengkap }}</strong></td>
                    <td style="text-align: center; font-weight: bold; text-transform: uppercase;">{{ str_replace('_', ' ', $p->status) }}</td>
                    <td>{{ $p->jam_masuk ? substr($p->jam_masuk, 0, 5) : '-' }} / {{ $p->jam_pulang ? substr($p->jam_pulang, 0, 5) : '-' }}</td>
                    <td>{{ $p->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #888; font-style: italic;">Tidak ada data presensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 25px; font-size: 9pt;">
        <tr>
            <td style="width: 50%; text-align: center;">
                Mengetahui,<br>Kepala Sekolah<br><br><br><br>
                <strong>Drs. H. Sudirman, M.Pd.</strong>
            </td>
            <td style="width: 50%; text-align: center;">
                Sleman, {{ date('d F Y') }}<br>Wali Kelas<br><br><br><br>
                <strong>{{ $kelas->waliKelas?->nama_gelar ?? 'Guru Kelas' }}</strong>
            </td>
        </tr>
    </table>
</body>
</html>
