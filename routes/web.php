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
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\SertifikatPeserta;
use App\Http\Controllers\NilaiController;
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
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\StatusMagangController;
use App\Http\Controllers\DashboardPembimbingController;
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
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index');
    }
    return view('landing_page');
});


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
        // nilai
        Route::resource('/nilai', NilaiController::class)->only(['index']);
        Route::get('/nilai/{peserta_id}/pdf', [NilaiController::class, 'exportPdf'])->name('nilai.exportPdf');
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
        // pendaftaran
        Route::resource('/pendaftaran', PendaftaranController::class)->only(['index']);
        Route::post('/pendaftaran/{id}/approve', [PendaftaranController::class, 'approve'])->name('pendaftaran.approve');
        Route::post('/pendaftaran/{id}/reject', [PendaftaranController::class, 'reject'])->name('pendaftaran.reject');
        // Sertifikat (Admin)
        Route::get('/admin/sertifikat-persetujuan', [SertifikatController::class, 'indexAdmin'])->name('sertifikat.indexAdmin');

        // Rute untuk halaman Status Magang
        Route::get('/status-magang', [StatusMagangController::class, 'index'])->name('status_magang.index');
        // Rute untuk menampilkan form edit status
        Route::get('/status-magang/edit', [StatusMagangController::class, 'edit'])->name('status_magang.edit');
    });

    Route::middleware('role:pembimbing')->group(function () {
         // nilai
        Route::resource('/nilai', NilaiController::class)->only(['index']);
        Route::get('/nilai/create', [NilaiController::class, 'create'])->name('nilai.create');
        Route::get('/nilai/{peserta_id}', [NilaiController::class, 'show'])->name('nilai.show');
        // Sertifikat (Pembimbing)
        Route::get('/pembimbing/sertifikat-persetujuan', [SertifikatController::class, 'indexPembimbing'])->name('sertifikat.indexPembimbing');
    });

    Route::middleware('role:user')->name('home.')->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('index');
        // desctination after scan qrcode
        Route::post('/absensi/qrcode', [HomeController::class, 'sendEnterPresenceUsingQRCode'])->name('sendEnterPresenceUsingQRCode');
        Route::post('/absensi/qrcode/out', [HomeController::class, 'sendOutPresenceUsingQRCode'])->name('sendOutPresenceUsingQRCode');

        Route::get('/absensi/{absensi}', [HomeController::class, 'show'])->name('show');
        Route::get('/absensi/{absensi}/izin', [HomeController::class, 'permission'])->name('permission');
        
        // Sertifikat (Peserta)
        Route::get('/sertifikat/pengajuan', [SertifikatController::class, 'indexPeserta'])->name('sertifikat.indexPeserta');



        // Route untuk menyimpan perubahan (update) kegiatan
        Route::put('home/kegiatan/{id}', [KegiatanController::class, 'update'])->name('home.kegiatan.update');
    });

    Route::get('/sertifikat/{peserta_id}/download', [SertifikatController::class, 'downloadCertificate'])->name('peserta.sertifikat.download');

    Route::middleware('role:admin,pembimbing,user')->group(function() {
        // account
        Route::resource('/account', AccountController::class)->only(['index']);
        Route::put('/account/edit', [AccountController::class, 'update'])->name('account.update');
        Route::get('/surat', [TestController::class, 'test'])->name('test.surat');

        // kegiatan
        Route::resource('/kegiatan', KegiatanController::class)->only(['index', 'create']);
        Route::get('kegiatan/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    });

    Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware('guest')->group(function () {
    // auth
    Route::get('/login', [AuthController::class, 'index'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/surat', [TestController::class, 'test'])->name('test.surat');
    
});

// pendaftaran peserta
// Route::get('/pendaftaran', PendaftaranIndex::class)->name('pendaftaran.index');
// Route::get('/pendaftaran/create', PendaftaranCreateForm::class)->name('pendaftaran.create');
