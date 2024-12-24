<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Peserta</title>
</head>
<body>
    <h1>Halo, {{ $peserta['name'] }}!</h1>
    <p>Selamat, akun Anda telah berhasil dibuat.</p>
    <p>Detail akun Anda:</p>
    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>

    </ul>
    <p>Silakan login ke aplikasi kami melalui tautan berikut:</p>
    <a href="{{ url('/login') }}">Login Sekarang</a>
    <br>
    <p>Terima kasih!</p>
</body>
</html>
