<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF - Kehadiran Peserta</title>
    <style>
        body {
            margin: 20px;
            font-family: Arial, sans-serif;
        }

        .kop {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 20px;
            margin: 5px 0;
        }

        p {
            margin: 2px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        @page {
            size: A4;
            margin: 20mm;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            width: 100%;
            text-align: right; /* Teks tanda tangan di kanan */
            font-size: 12px;
        }

        .footer .signature {
            display: inline-block;
            text-align: center;
            width: 40%; /* Lebar area tanda tangan */
            margin-right: 20px;
        }

        .footer .signature p {
            margin: 0;
        }

        .signature-space {
            height: 80px; /* Ruang kosong untuk tanda tangan */
        }

        .dashed-line {
            width: 100%;
            border-top: 1px dashed black;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="kop">
        <h1>DINAS KETAHANAN PANGAN, PERTANIAN DAN PERIKANAN KOTA BANJARMASIN</h1>
        <p>Jl. Lkr. Dalam Utara Benua Anyar, Kota Banjarmasin</p>
        <p>Telepon: (021) 12345678 | Email: info@dkp3.go.id</p>
    </div>

    <hr>

    <h3 style="text-align: center;">Laporan Kehadiran Peserta Magang</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Bidang</th>
                <th>Tanggal Hadir</th>
                <th>Absen Masuk</th>
                <th>Absen Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedData as $data)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->user->peserta->name ?? 'N/A' }}</td>
                    <td>{{ $data->user->peserta->bidang->name ?? 'N/A' }}</td>
                    <td>{{ $data->tgl_hadir }}</td>
                    <td>{{ $data->absen_masuk }}</td>
                    <td>{{ $data->absen_keluar ?? 'Belum Absen' }}</td>
                    <td>{{ $data->izin ? 'Izin' : 'Hadir' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui, Pembimbing Lapangan</p>
            <div class="signature-space"></div>
            <div class="dashed-line"></div> <!-- Garis putus-putus -->
            <p>NIP : </p>
        </div>
    </div>
</body>
</html>
