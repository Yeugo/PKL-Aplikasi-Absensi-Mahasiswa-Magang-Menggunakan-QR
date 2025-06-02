{{--
    Pastikan semua CSS Bootstrap dan SB Admin 2 kamu aktif kembali.
    Hapus atau komentari @vite di base.blade.php jika masih ada.
--}}
<link rel="stylesheet" href="{{ asset('bootstrap5/css/bootstrap.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

<link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

{{-- Ini adalah CSS KUSTOM UTAMAMU untuk Neobrutalism --}}
{{-- PENTING: Ini harus dimuat PALING AKHIR agar bisa menimpa Bootstrap --}}
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
{{-- Atau kamu bisa pakai main.css jika itu file yang kamu pakai --}}
{{-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> --}}

@livewireStyles

{{-- Favicon (jika mau) --}}
{{-- <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png"> --}}