<?php

use Livewire\Livewire;
use App\Models\Peserta;
use App\Models\Kegiatan;
use FontLib\Table\Type\name;
use App\Http\Livewire\InternTable;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\PendaftaranIndex;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Livewire\PendaftaranCreateForm;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\DashboardPembimbingController;
use App\Http\Controllers\PendaftaranController;
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
    Route::middleware('role:admin,pembimbing')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');       
        // absensis (absensi)
        Route::resource('/absensi', AbsensiController::class)->only(['index', 'create']);
        Route::get('/absensi/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
        // peserta
        Route::resource('/peserta', PesertaController::class)->only(['index', 'create']);
        Route::get('peserta/edit', [PesertaController::class, 'edit'])->name('peserta.edit');
        // presences (kehadiran)
        Route::resource('/kehadiran', KehadiranController::class)->only(['index']);
        Route::get('/kehadiran/qrcode', [KehadiranController::class, 'showQrcode'])->name('kehadiran.qrcode');
        Route::get('/kehadiran/qrcode/download-pdf', [KehadiranController::class, 'downloadQrCodePDF'])->name('kehadiran.qrcode.download-pdf');
        Route::get('/kehadiran/{absensi}', [KehadiranController::class, 'show'])->name('kehadiran.show');
        // kegiatan
        Route::resource('/kegiatan', KegiatanController::class)->only(['index']);
        Route::get('kegiatan/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
        // not present data
        Route::get('/kehadiran/{absensi}/not-present', [KehadiranController::class, 'notPresent'])->name('kehadiran.not-present');
        Route::post('/kehadiran/{absensi}/not-present', [KehadiranController::class, 'notPresent']);
        // present (url untuk menambahkan/mengubah user yang tidak hadir menjadi hadir)
        Route::post('/kehadiran/{absensi}/present', [KehadiranController::class, 'presentUser'])->name('kehadiran.present');
        Route::post('/kehadiran/{absensi}/acceptPermission', [KehadiranController::class, 'acceptPermission'])->name('kehadiran.acceptPermission');
        // employees permissions
        Route::get('/kehadiran/{absensi}/izin', [KehadiranController::class, 'permissions'])->name('kehadiran.izin');
    });

    Route::middleware('role:admin')->group(function () {
        // users
        Route::resource('/users', UserController::class)->only(['index', 'create']);
        Route::get('/users/edit', [UserController::class, 'edit'])->name('users.edit');
        // holidays (hari libur)
        Route::resource('/holidays', HolidayController::class)->only(['index', 'create']);
        Route::get('/holidays/edit', [HolidayController::class, 'edit'])->name('holidays.edit');
        // pembimbing
        Route::resource('/pembimbing', PembimbingController::class)->only(['index', 'create']);
        Route::get('pembimbing/edit', [PembimbingController::class, 'edit'])->name('pembimbing.edit');
        // department
        Route::resource('/bidangs', BidangController::class)->only(['index', 'create']);
        Route::get('/bidangs/edit', [BidangController::class, 'edit'])->name('bidangs.edit');

        Route::resource('/pendaftaran', PendaftaranController::class)->only(['index']);
        Route::post('/pendaftaran/{id}/approve', [PendaftaranController::class, 'approve'])->name('pendaftaran.approve');
        Route::post('/pendaftaran/{id}/reject', [PendaftaranController::class, 'reject'])->name('pendaftaran.reject');  
    });

    Route::middleware('role:user')->name('home.')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('index');
        // desctination after scan qrcode
        Route::post('/absensi/qrcode', [HomeController::class, 'sendEnterPresenceUsingQRCode'])->name('sendEnterPresenceUsingQRCode');
        Route::post('/absensi/qrcode/out', [HomeController::class, 'sendOutPresenceUsingQRCode'])->name('sendOutPresenceUsingQRCode');

        Route::get('/absensi/{absensi}', [HomeController::class, 'show'])->name('show');
        Route::get('/absensi/{absensi}/izin', [HomeController::class, 'permission'])->name('permission');
        // kegiatan
        Route::get('/kegiatanpeserta', [KegiatanController::class, 'indexPeserta'])->name('kegiatanPeserta');
        Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
        Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
        Route::get('home/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('home.kegiatan.edit');
        // Route untuk menyimpan perubahan (update) kegiatan
        Route::put('home/kegiatan/{id}', [KegiatanController::class, 'update'])->name('home.kegiatan.update');
    });

    Route::middleware('role:admin,pembimbing,user')->group(function() {
        // account
        Route::resource('/account', AccountController::class)->only(['index']);
        Route::put('/account/edit', [AccountController::class, 'update'])->name('account.update');
    });

    Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware('guest')->group(function () {
    // auth
    Route::get('/login', [AuthController::class, 'index'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    
});

// pendaftaran peserta
// Route::get('/pendaftaran', PendaftaranIndex::class)->name('pendaftaran.index');
// Route::get('/pendaftaran/create', PendaftaranCreateForm::class)->name('pendaftaran.create');
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');