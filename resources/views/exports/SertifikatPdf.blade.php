<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Magang - {{ $peserta->name ?? 'Peserta' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0; /* Penting agar background image bisa memenuhi halaman */
        }

        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 0;
            width: 297mm; /* A4 landscape width */
            height: 210mm; /* A4 landscape height */
            box-sizing: border-box;
            /* Background image untuk border */
            background-image: url("{{ $borderImageBase64 ?? '' }}");
            background-size: 100% 100%; /* Memastikan gambar mengisi seluruh body */
            background-repeat: no-repeat;
            position: relative; /* Diperlukan jika ada elemen anak yang absolute terhadap body */
        }

        /* Container untuk semua konten teks dan logo, di dalam area "bersih" dari gambar border */
        .content-container {
            position: absolute;
            /* Sesuaikan nilai top, left, right, bottom ini dengan area bersih pada gambar border Anda */
            /* Contoh: jika gambar border Anda memiliki margin visual 15mm di setiap sisi */
            top: 15mm;
            left: 5mm;
            right: 50mm;
            bottom: 30mm;
            box-sizing: border-box;
            position: relative; /* Konteks posisi untuk elemen anak absolut seperti logo dan signature-area */
        }

        .header-logo {
            position: absolute;
            top: 0mm;    /* Relatif terhadap .content-container */
            left: 0mm;   /* Relatif terhadap .content-container */
            height: 35px;
            width: auto;
            z-index: 10;
        }

        .certificate-content-wrapper {
            width: 100%; /* Mengisi lebar .content-container */
            box-sizing: border-box;
            text-align: center;
            padding-bottom: 0; /* Dihilangkan karena signature-area absolut */
            /* padding-top: 40px; /* Jika logo di atas dan butuh space, sesuaikan */
        }

        .certificate-main-content {
            color: #333;
            padding-top: 0mm; /* Sesuaikan jika ada logo di atasnya */
        }

        .certificate-title {
            font-family: Arial, sans-serif;
            font-size: 30pt;
            font-weight: bold;
            color: #0056b3; /* Anda mungkin ingin warna ini senada dengan image border baru Anda */
            margin-top: 10mm; /* Sesuaikan dengan keberadaan logo */
            margin-bottom: 3mm;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .award-phrase {
            font-size: 13pt;
            margin-top: 3mm;
            margin-bottom: 3mm;
        }

        .peserta-name {
            font-family: 'Georgia', serif;
            font-size: 26pt;
            font-weight: bold;
            color: #000;
            margin-top: 3mm;
            margin-bottom: 5mm;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .acknowledgement-text {
            font-size: 12pt;
            margin-top: 3mm;
            margin-bottom: 3mm;
            line-height: 1.3;
        }

        .internship-field {
            font-size: 14pt;
            font-weight: bold;
            color: #000;
            margin-top: 3mm;
            margin-bottom: 5mm;
        }

        .decorative-hr {
            border: 0;
            height: 0.5px;
            background-color: #a0a0a0;
            margin: 8mm auto;
            width: 70%;
        }

        .magang-details {
            margin-top: 5mm;
            margin-bottom: 5mm;
        }

        .detail-row {
            font-size: 11pt;
            margin-bottom: 3px;
            text-align: left;
            margin-left: 110mm
        }

        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 110px;
            text-align: left;
            margin-right: 3px;
        }

        .detail-colon {
            display: inline-block;
            margin-right: 3px;
        }

        .detail-value {
            display: inline-block;
            text-align: left;
        }

        .signature-area {
            /* position: absolute; */
            bottom: 0mm;  /* Relatif terhadap .content-container */
            left: 0mm;    /* Relatif terhadap .content-container */
            right: 0mm;   /* Relatif terhadap .content-container */
            width: 100%;  /* Mengisi lebar .content-container */
            box-sizing: border-box;
            text-align: center;
            padding-top: 10mm; /* Padding internal di atas blok tanda tangan */
            height: 65mm;     /* Tinggi area tanda tangan yang ditetapkan */
        }

        .signature-block {
            display: inline-block;
            width: 48%;
            text-align: center;
            vertical-align: top;
            box-sizing: border-box;
        }

        .signature-block + .signature-block {
            margin-left: 2%;
        }

        .signature-block-title {
            font-size: 11pt;
            text-align: center;
            margin-bottom: 3px;
        }

        .signature-line {
            border-bottom: 1px dashed #555;
            margin-top: 20mm;
            margin-bottom: 3px;
            position: relative;
            height: 30px;
            width: 25%;
            margin-left: auto;
            margin-right: auto;
        }

        .signature-name {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 1px;
            margin-top: 3px;
        }

        .signature-title-nip {
            font-size: 9pt;
            margin-top: 0;
        }

        .img-signature {
            position: absolute;
            width: 80px;
            height: auto;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }

        .img-stamp {
            position: absolute;
            width: 60px;
            height: auto;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.75;
            z-index: 2;
        }
    </style>
</head>
<body>
    {{-- Gambar border diatur sebagai background body --}}

    <div class="content-container">
        @if(isset($logoBase64) && $logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo Dinas" class="header-logo">
        @endif

        <div class="certificate-content-wrapper">
            <div class="certificate-main-content">
                <h1 class="certificate-title">Sertifikat Magang</h1>
                <p class="award-phrase">Diberikan Kepada:</p>
                <p class="peserta-name"><u>{{ $peserta->name ?? 'NAMA PESERTA' }}</u></p>
                <p class="acknowledgement-text">Atas partisipasi aktif dan penyelesaian program magang sebagai:</p>
                <p class="internship-field">"{{ $peserta->bidang->name ?? 'Bidang Magang' }}"</p>
                <p class="acknowledgement-text">di Dinas Ketahanan Pangan, Pertanian dan Perikanan Kota Banjarmasin.</p>
                <hr class="decorative-hr">
                <div class="magang-details">
                    <div class="detail-row">
                        <span class="detail-label">NPM</span><span class="detail-colon">:</span><span class="detail-value">{{ $peserta->npm ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Universitas</span><span class="detail-colon">:</span><span class="detail-value">{{ $peserta->univ ?? '-' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Periode</span><span class="detail-colon">:</span><span class="detail-value">{{ $peserta->tgl_mulai_magang ? \Carbon\Carbon::parse($peserta->tgl_mulai_magang)->translatedFormat('d F Y') : '-' }} s.d. {{ $peserta->tgl_selesai_magang_rencana ? \Carbon\Carbon::parse($peserta->tgl_selesai_magang)->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    @if(isset($rata2) && $rata2 > 0)
                        <div class="detail-row">
                            <span class="detail-label">Nilai Kinerja</span><span class="detail-colon">:</span><span class="detail-value">{{ number_format($rata2, 2) }} ({{ $kategori ?? '' }})</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="signature-area">
            <div class="signature-block">
                <p class="signature-block-title">Pembimbing Lapangan</p>
                <div class="signature-line">
                    @if(isset($signaturePembimbingBase64) && $signaturePembimbingBase64)
                        <img src="{{ $signaturePembimbingBase64 }}" class="img-signature" alt="Tanda Tangan Pembimbing">
                    @endif
                </div>
                <p class="signature-name">{{ $peserta->pembimbing->name ?? 'Nama Pembimbing' }}</p>
                <p class="signature-title-nip">NIP. {{ $peserta->pembimbing->nip ?? '-' }}</p>
            </div>
            <div class="signature-block">
                <p class="signature-block-title">{{ $kotaPenerbit ?? 'Banjarmasin' }}, {{ $issueDate ?? \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="signature-block-title" style="margin-top: 5px;">Kepala Dinas</p>
                <div class="signature-line">
                    @if(isset($signatureAdminBase64) && $signatureAdminBase64)
                        <img src="{{ $signatureAdminBase64 }}" class="img-signature" alt="Tanda Tangan Kepala Dinas">
                    @endif
                    @if(isset($stampBase64) && $stampBase64)
                        <img src="{{ $stampBase64 }}" class="img-stamp" alt="Stempel Dinas">
                    @endif
                </div>
                <p class="signature-name">{{ $namaKepalaDinas ?? 'Nama Kepala Dinas' }}</p>
                <p class="signature-title-nip">NIP. {{ $nipKepalaDinas ?? 'NIP KEPALA DINAS' }}</p>
            </div>
        </div>
    </div> {{-- End .content-container --}}
</body>
</html>