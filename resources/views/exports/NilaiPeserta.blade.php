<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penilaian Peserta - {{ $peserta->name ?? 'Peserta' }}</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            /* margin: 25mm 20mm 25mm 20mm;  */
        }

        .header { text-align: center; }
        .kop { font-family: Arial, sans-serif; font-size: 14pt; font-weight: bold; margin-left: 25px }
        .subkop { font-family: Arial, sans-serif; font-size: 12pt; margin-left: 25px }
        .line { border-top: 2px solid #000; margin: 8px 0 8px 0; }

        .report-title {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10pxpx;
        }

        .participant-details-section {
            margin-bottom: 20px;
            font-size: 11pt;
            padding-left: 20px;
        }

        .participant-details-section p {
            margin: 4px 0;
            display: flex;
            align-items: baseline;
        }

        .participant-details-section p strong {
            display: inline-block;
            width: 110px; /* Sesuaikan lebar ini agar ':' sejajar rapi. Contoh: "Universitas", "Pembimbing" butuh lebih lebar */
            margin-right: 5px;
        }

        .section-title {
            font-size: 13pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc; /* Garis bawah untuk judul bagian */
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10pt;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        /* Warna kategori (Dompdf tidak mendukung semua kelas warna Bootstrap, jadi kita definisi ulang) */
        .text-success { color: #198754; } /* Green */
        .text-info { color: #0dcaf0; } /* Cyan */
        .text-warning { color: #ffc107; } /* Yellow */
        .text-danger { color: #dc3545; } /* Red */
        .text-muted { color: #6c757d; } /* Gray */

        /* Styling untuk footer (tanda tangan) */
        .signature-section {
            width: 100%;
            margin-top: 60px; /* Jarak dari tabel ke tanda tangan */
            font-size: 11pt; /* Ukuran font tanda tangan */
            text-align: center; /* Pusatkan seluruh blok tanda tangan */
            /* Hapus display: flex, justify-content, flex-wrap, gap */
        }

        .signature-block {
            display: inline-block; /* <<< PENTING: Membuat blok sejajar */
            width: 48%; /* <<< Beri lebar tetap untuk setiap blok */
            text-align: center;
            vertical-align: top; /* <<< Penting untuk memastikan mereka sejajar di bagian atas */
            /* Tambahkan sedikit margin-left pada blok kedua agar ada jarak di tengah */
            margin-left: 2%; /* Jarak antara blok tanda tangan */
            box-sizing: border-box; /* Untuk memastikan width termasuk padding/border */
        }

        /* Override margin-left untuk blok pertama agar tidak ada spasi ekstra di kiri */
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

        @page {
            size: A4;
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

    <div class="report-title">
        <h3>LAPORAN PENILAIAN KINERJA PESERTA MAGANG</h3>
    </div>

    {{-- START: Bagian Data Diri Peserta --}}
    <div class="section-title">Informasi Peserta</div>
    <div class="participant-details-section">
        <p><strong>Nama</strong>       : {{ $peserta->name ?? '-' }}</p>
        <p><strong>NIM</strong>          : {{ $peserta->npm ?? '-' }}</p>
        <p><strong>Universitas</strong>  : {{ $peserta->univ ?? '-' }}</p>
        <p><strong>Pembimbing</strong>   : {{ $peserta->pembimbing->name ?? '-' }}</p>
        <p><strong>Bidang</strong>       : {{ $peserta->bidang->name ?? '-' }}</p>
        {{-- Jika periode magang diperlukan, uncomment baris ini --}}
        {{-- <p><strong>Periode Magang</strong>: {{ $peserta->periode ?? '-' }}</p> --}}
    </div>
    {{-- END: Bagian Data Diri Peserta --}}

    {{-- START: Bagian Penilaian Kinerja --}}
    <div class="section-title">Penilaian Kinerja</div>
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kriteria</th>
                <th class="text-center">Nilai (1-100)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $listNilai = [
                    'Sikap' => $nilai->sikap,
                    'Kedisiplinan' => $nilai->kedisiplinan,
                    'Kesungguhan' => $nilai->kesungguhan,
                    'Mandiri' => $nilai->mandiri,
                    'Kerjasama' => $nilai->kerjasama,
                    'Teliti' => $nilai->teliti,
                    'Pendapat' => $nilai->pendapat,
                    'Hal Baru' => $nilai->hal_baru,
                    'Inisiatif' => $nilai->inisiatif,
                    'Kepuasan' => $nilai->kepuasan,
                ];
                $total = array_sum($listNilai);
                $jumlahKriteria = count($listNilai);
                $rata2 = ($jumlahKriteria > 0) ? $total / $jumlahKriteria : 0;

                // Menentukan kategori berdasarkan rata-rata
                if ($rata2 >= 85) {
                    $kategori = 'Sangat Baik';
                    $kategoriClass = 'text-success';
                } elseif ($rata2 >= 70) {
                    $kategori = 'Baik';
                    $kategoriClass = 'text-info';
                } elseif ($rata2 >= 55) {
                    $kategori = 'Cukup';
                    $kategoriClass = 'text-warning';
                } else {
                    $kategori = 'Kurang';
                    $kategoriClass = 'text-danger';
                }
            @endphp

            @foreach ($listNilai as $kriteria => $nilaiItem)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $kriteria }}</td>
                    <td class="text-center">{{ $nilaiItem }}</td>
                </tr>
            @endforeach
            <tr class="fw-bold">
                <td colspan="2" class="text-end py-2">Total Nilai</td>
                <td class="text-center py-2">{{ $total }}</td>
            </tr>
            <tr class="fw-bold">
                <td colspan="2" class="text-end py-2">Rata-rata</td>
                <td class="text-center py-2 {{ $kategoriClass }}">
                    {{ number_format($rata2, 2) }} ({{ $kategori }})
                </td>
            </tr>
        </tbody>
    </table>
    {{-- END: Bagian Penilaian Kinerja --}}

    {{-- START: Bagian Catatan Pembimbing --}}
    {{-- <div class="section-title">Catatan Pembimbing</div>
    <div class="note-section">
        <p class="text-muted">{{ $nilai->catatan ?? 'Tidak ada catatan.' }}</p>
    </div> --}}
    {{-- END: Bagian Catatan Pembimbing --}}

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