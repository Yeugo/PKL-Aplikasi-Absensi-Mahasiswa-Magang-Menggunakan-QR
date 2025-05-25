<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Balasan Penerimaan Magang</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 12pt; }
        .header { text-align: center; }
        .kop { font-family: Arial, sans-serif; font-size: 14pt; font-weight: bold; }
        .subkop { font-family: Arial, sans-serif; font-size: 12pt; }
        .line { border-top: 2px solid #000; margin: 8px 0 16px 0; }
        .content { margin-top: 20px; }
        .indent { text-indent: 30px; }
        .ttd { margin-top: 60px; text-align: right; }
        .stamp { margin-top: 40px; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo-banjarmasin.png') }}" alt="Logo" style="height:70px;float:left;margin-right:10px;">
        <div>
            <div class="kop">PEMERINTAH KOTA BANJARMASIN</div>
            <div class="kop">DINAS KETAHANAN PANGAN, PERTANIAN DAN PERIKANAN</div>
            <div class="subkop">Komplek Screen House</div>
            <div class="subkop">Jl. Pangeran Hidayatullah / Lingkar Dalam Utara</div>
            <div class="subkop">Kel. Benua Anyar Kec. Banjarmasin Timur 70239 Email : distankan_bjm@yahoo.co.id</div>
        </div>
    </div>
    <div class="line"></div>
    <div style="text-align:right;">
        Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
    </div>
    <table style="margin-top:10px;">
        <tr>
            <td style="width:120px;">Nomor</td>
            <td>: 520/495-Sekr.Umpeg/DKP3</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>: -</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>: Kesediaan Menerima Praktek Kerja Mahasiswa/i</td>
        </tr>
    </table>
    <br>
    <div>
        Kepada Yth.<br>
        Dekan Fakultas Teknologi Informasi<br>
        Universitas Islam Kalimantan (UNISKA)<br>
        Muhammad Arsyad Al Banjari<br>
        di-<br>
        Banjarmasin
    </div>
    <div class="content">
        <p class="indent">
            Sehubungan dengan surat Saudara Nomor : 016/UNISKA-FTI/A.15/IX/2024<br>
            Tanggal {{ \Carbon\Carbon::parse($pendaftaran->created_at)->translatedFormat('d F Y') }}, Perihal Mohon Kesediaan Menerima Praktek Kerja Mahasiswa/i pada Dinas Ketahanan Pangan, Pertanian dan Perikanan Kota Banjarmasin, an:
        </p>
        <p class="bold">
            1. {{ $peserta->name ?? 'NAMA PESERTA' }} &nbsp;&nbsp;&nbsp;&nbsp; NPM : {{ $peserta->npm ?? 'NPM' }}
        </p>
        <p>
            Berdasarkan pertimbangan, Kami <span class="bold">BERSEDIA</span> menerima mahasiswa dimaksud untuk melaksanakan Praktek Kerja Lapangan di Dinas Ketahanan Pangan, Pertanian dan Perikanan, sesuai waktu yang telah ditentukan, selama 2 (dua) bulan, dimulai tanggal 09 September s.d 08 November 2024, dengan ketentuan agar bersedia mengikuti peraturan dan tata tertib yang berlaku.
        </p>
        <p>
            Demikian disampaikan, atas kerjasama yang baik diucapkan terima kasih.
        </p>
    </div>
    <div class="ttd">
        <div class="bold">Disahkan secara elektronik oleh:</div>
        <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
        <p class="bold">Hj. Ruziah, SE, M.AP</p>
        <p>Pembina Tingkat I</p>
        <p>NIP. 19680714 199503 2 004</p>
    </div>
    {{-- Jika ingin menambahkan stempel, bisa gunakan gambar PNG transparan --}}
    {{-- <div class="stamp"><img src="{{ public_path('stempel.png') }}" alt="Stempel" style="height:80px;"></div> --}}
</body>
</html>
