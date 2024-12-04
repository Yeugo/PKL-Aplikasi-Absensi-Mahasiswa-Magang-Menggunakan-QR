<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PresenceController;
use App\Http\Livewire\InternTable;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        // interns
        Route::resource('/interns', InternController::class)->only(['index', 'create']);
        Route::get('/interns/edit', [InternController::class, 'edit'])->name('interns.edit');
        // holidays (hari libur)
        Route::resource('/holidays', HolidayController::class)->only(['index', 'create']);
        Route::get('/holidays/edit', [HolidayController::class, 'edit'])->name('holidays.edit');
        // absensis (absensi)
        Route::resource('/absensi', AbsensiController::class)->only(['index', 'create']);
        Route::get('/absensi/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
        // department
        Route::resource('/bidangs', BidangController::class)->only(['index', 'create']);
        Route::get('/bidangs/edit', [BidangController::class, 'edit'])->name('bidangs.edit');

        
        // presences (kehadiran)
        Route::resource('/kehadiran', KehadiranController::class)->only(['index']);
        Route::get('/kehadiran/qrcode', [KehadiranController::class, 'showQrcode'])->name('kehadiran.qrcode');
        Route::get('/kehadiran/qrcode/download-pdf', [KehadiranController::class, 'downloadQrCodePDF'])->name('kehadiran.qrcode.download-pdf');
        Route::get('/kehadiran/{absensi}', [KehadiranController::class, 'show'])->name('kehadiran.show');
        // not present data
        Route::get('/kehadiran/{absensi}/not-present', [KehadiranController::class, 'notPresent'])->name('kehadiran.not-present');
        Route::post('/kehadiran/{absensi}/not-present', [KehadiranController::class, 'notPresent']);
        // present (url untuk menambahkan/mengubah user yang tidak hadir menjadi hadir)
        Route::post('/kehadiran/{absensi}/present', [KehadiranController::class, 'presentUser'])->name('kehadiran.present');
        Route::post('/kehadiran/{absensi}/acceptPermission', [KehadiranController::class, 'acceptPermission'])->name('kehadiran.acceptPermission');
        // employees permissions

        Route::get('/kehadiran/{absensi}/izin', [KehadiranController::class, 'permissions'])->name('kehadiran.izin');
    });

    Route::middleware('role:user')->name('home.')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('index');
        // desctination after scan qrcode
        Route::post('/absensi/qrcode', [HomeController::class, 'sendEnterPresenceUsingQRCode'])->name('sendEnterPresenceUsingQRCode');
        Route::post('/absensi/qrcode/out', [HomeController::class, 'sendOutPresenceUsingQRCode'])->name('sendOutPresenceUsingQRCode');

        Route::get('/absensi/{absensi}', [HomeController::class, 'show'])->name('show');
        Route::get('/absensi/{absensi}/izin', [HomeController::class, 'izin'])->name('izin');
    });

    Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware('guest')->group(function () {
    // auth
    Route::get('/login', [AuthController::class, 'index'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});
