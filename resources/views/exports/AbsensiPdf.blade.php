<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF - Data Karyawan</title>
    <style>
        body {
            /* font-family: Arial, sans-serif; */
            margin: 20px;
        }

        .kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .kop img {
            width: 80px;
            height: auto;
        }

        .kop .text {
            text-align: center;
            flex: 1;    
        }

        .kop h1 {
            margin: 10px 0 5px;
            font-size: 20px;
        }

        .kop p {
            margin: 5px 0;
            font-size: 14px;
        }

        hr {
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
            /* Ukuran font diperkecil */
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        @page {
            size: A4;
            margin: 20mm;
        }

    </style>
</head>

<body>
    <div class="kop">
        <div class="text">
            <h1>DINAS KETAHANAN PANGAN, PERTANIAN DAN PERIKANAN KOTA BANJARMASIN</h1>
            <p>Jl. Lkr. Dalam Utara Benua Anyar, Kota Banjarmasin</p>
            <p>Telepon: (021) 12345678 | Email: info@dkp3.go.id</p>
        </div>
    </div>

    <hr>

    <h3 style="text-align: center;">Jadwal Absensi Peserta Magang</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Absensi</th>
                <th>Keterangan</th>
                <th>Waktu Absen Masuk</th>
                <th>Waktu Absen Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedData as $data)
            <tr>
                <td>{{ $data->id }}</td>
                <td>{{ $data->title }}</td>
                <td>{{ $data->description }}</td>
                <td>{{ $data->start_time }}</td>
                <td>{{ $data->end_time }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
