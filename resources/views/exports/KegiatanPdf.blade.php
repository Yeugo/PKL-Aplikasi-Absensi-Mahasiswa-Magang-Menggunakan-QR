<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF - Kehadiran Peserta</title>
    <style>
        body {
            font-family: "Times New Roman", serif; 
            font-size: 12pt;
            margin : 0;
            padding: 0;
            position: relative; 
            min-height: 100vh; /* Pastikan body setidaknya setinggi viewport */
        }

        /* --- Page Wrapper for Bottom Positioning --- */
        .page-wrapper {
            position: relative; /* Critical: This makes absolute positioning of signature relative to this div */
            min-height: 100vh; /* Ensures the wrapper is at least full viewport height */
            /* Add padding-bottom to create space for the absolutely positioned signature */
            padding-bottom: 200px; /* Adjust this value based on the actual height of your signature block */
            box-sizing: border-box; /* Include padding in the height calculation */
            margin: 25mm 20mm 25mm 20mm; /* Apply your page margins here for the content */
        }

        .header { text-align: center; }
        .kop { font-family: Arial, sans-serif; font-size: 14pt; font-weight: bold; margin-left: 25px }
        .subkop { font-family: Arial, sans-serif; font-size: 12pt; margin-left: 25px }
        .line { border-top: 2px solid #000; margin: 8px 0 16px 0; }
        .content { margin-top: 20px; }
        .indent { text-indent: 30px; }
        .ttd { margin-top: 60px; text-align: right; }
        .stamp { margin-top: 40px; }
        .bold { font-weight: bold; }

        .judul {
            font-size: 14px;
            font-weight: bold;
            text-align: left;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .datadiri {
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        table, th, td {
            border: 0.7px solid black;
        }

        th, td {
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        /* Adjust page-wrapper margins for print if @page margins are set */
        @media print {
            .page-wrapper {
                margin: 0; /* Remove internal margin if @page handles it */
                padding-bottom: 200px; /* Keep padding for signature space */
                min-height: auto; /* Allow content to flow across pages */
            }
            .signature-section {
                /* For multi-page documents, `position: absolute` will keep it at the bottom
                   of the *last* page if the content fills up previous pages and the
                   .page-wrapper expands. If you need it on every page, you'd need
                   a PDF-specific header/footer feature from your library. */
            }
        }

        /* Styling for footer (tanda tangan) */
        .signature-section {
            position: absolute; /* Key: Absolute positioning */
            bottom: 0; /* Key: Position at the bottom of the parent .page-wrapper */
            left: 0;
            right: 0;
            width: 100%;
            font-size: 11pt;
            text-align: center;
            /* margin-top will no longer work due to absolute positioning, use padding-bottom on .page-wrapper instead */
        }

        .signature-block {
            display: inline-block;
            width: 48%; /* Adjust width as needed for spacing */
            text-align: center;
            vertical-align: top;
            margin-left: 2%; /* Gap between blocks */
            box-sizing: border-box;
        }

        .signature-block:first-child {
            margin-left: 0;
        }

        .signature-space {
            height: 70px;
        }

        .dashed-line {
            width: 80%;
            border-top: 1px dashed black;
            margin: 5px auto 0 auto;
        }

        .nip-line {
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $base64Image }}" alt="Logo" style="height:70px; float:left; width:auto; position: relative; z-index: 1; margin-top: 10px;">
        <div>
            <div class="kop">PEMERINTAH KOTA BANJARMASIN</div>
            <div class="kop">DINAS KETAHANAN PANGAN, PERTANIAN DAN PERIKANAN</div>
            <div class="subkop">Komplek Screen House</div>
            <div class="subkop">Jl. Pangeran Hidayatullah / Lingkar Dalam Utara</div>
            <div class="subkop">Kel. Benua Anyar Kec. Banjarmasin Timur 70239 Email : distankan_bjm@yahoo.co.id</div>
        </div>
    </div>
    <div class="line"></div>

    <div class="judul">AGENDA KEGIATAN PRAKTEK KERJA LAPANGAN</div>
    @php
        $datadiri = $selectedData->first(); // Ambil peserta pertama dari koleksi
    @endphp
    <div class="datadiri">NPM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $datadiri->peserta->npm }}</div>
    <div class="datadiri">Nama Peserta&nbsp;: {{ $datadiri->peserta->name }}</div>
    <div class="datadiri">Universitas&nbsp;&nbsp;&nbsp;&nbsp;   : {{ $datadiri->peserta->univ }}</div>
    <div class="datadiri">Pembimbing&nbsp;&nbsp;&nbsp;: {{ $datadiri->peserta->pembimbing->name }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kegiatan</th>
                <th>Deskripsi</th>
                <th>Tanggal Kegiatan</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Bidang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedData as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->judul }}</td>
                    <td>{{ $data->deskripsi }}</td>
                    <td>{{ $data->tgl_kegiatan }}</td>
                    <td>{{ $data->waktu_mulai }}</td>
                    <td>{{ $data->waktu_selesai }}</td>
                    <td>{{ $data->peserta->bidang->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- <div class="footer">
        <div class="signature">
            <p>Banjarmasin, {{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('d F Y') }}</p>
            <p>Mengetahui, Pembimbing Lapangan</p>
            <div class="signature-space"></div>
            <div class="dashed-line"></div> <!-- Garis putus-putus -->
            <p>NIP : </p>
        </div>
    </div> --}}

    <div class="signature-section">
        <div class="signature-block">
            <p>Mengetahui,</p>
            <p>Pimpinan</p>
            <div class="signature-space"></div>
            <div class="dashed-line"></div>
            <p class="nip-line">NIP : </p>
        </div>
        <div class="signature-block">
            <p>Banjarmasin, {{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('d F Y') }}</p>
            <p>Pembimbing Lapangan</p>
            <div class="signature-space"></div>
            <div class="dashed-line"></div>
            <p class="nip-line">NIP : </p>
        </div>
    </div>
</body>
</html>
