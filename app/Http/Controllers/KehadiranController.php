<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Models\Permission;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Kehadiran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class KehadiranController extends Controller
{
    public function index()
    {
        $absensis = Absensi::all()->sortByDesc('data.is_end')->sortByDesc('data.is_start');

        return view('kehadiran.index', [
            "title" => "Daftar Absensi Dengan Kehadiran",
            "absensis" => $absensis
        ]);
    }

    public function show(Absensi $absensi)
    {
        $absensi->load(['kehadiran']);

        // dd($qrcode);
        return view('kehadiran.show', [
            "title" => "Data Detail Kehadiran",
            "absensi" => $absensi,
        ]);
    }

    public function showQrcode()
    {
        $code = request('code');

        $absensi = Absensi::where('code', $code)->first();

        $newCode = Str::random();

        $absensi->update([
            'code' => $newCode, // Update kode absensi
        ]);

        $qrcode = $this->getQrCode($newCode);

        return view('kehadiran.qrcode', [
            "title" => "Generate Absensi QRCode",
            "qrcode" => $qrcode,
            "code" => $newCode
        ]);
        

        // $code = request('code');
    
        // $qrcode = $this->getQrCode($code);

        // return view('kehadiran.qrcode', [
        //     "title" => "Generate Absensi QRCode",
        //     "qrcode" => $qrcode,
        //     "code" => $code
        // ]);
    }

    public function downloadQrCodePDF()
    {
        $code = request('code');
        $qrcode = $this->getQrCode($code);

        $html = '<img src="' . $qrcode . '" />';
        return Pdf::loadHTML($html)->setWarnings(false)->download('qrcode.pdf');
    }

    public function getQrCode(?string $code): string
    {
        if (!Absensi::query()->where('code', $code)->first())
            throw new NotFoundHttpException(message: "Tidak ditemukan absensi dengan code '$code'.");

        return parent::getQrCode($code);
    }

    public function notPresent(Absensi $absensi)
    {
        $byDate = now()->toDateString();
        if (request('display-by-date'))
            $byDate = request('display-by-date');

        $kehadirans = Kehadiran::query()
            ->where('absensi_id', $absensi->id)
            ->where('tgl_hadir', $byDate)
            ->get(['tgl_hadir', 'user_id']);

        // jika semua karyawan tidak hadir
        if ($kehadirans->isEmpty()) {
            $notPresentData[] =
                [
                    "not_presence_date" => $byDate,
                    "users" => User::query()
                        ->onlyEmployees()
                        ->get()
                        ->toArray()
                ];
        } else {
            $notPresentData = $this->getNotPresentEmployees($kehadirans);
        }


        return view('kehadiran.not-present', [
            "title" => "Data Karyawan Tidak Hadir",
            "absensi" => $absensi,
            "notPresentData" => $notPresentData
        ]);
    }

    public function permissions(Absensi $absensi)
    {
        $byDate = now()->toDateString();
        if (request('display-by-date'))
            $byDate = request('display-by-date');

        $izins = Izin::query()
            ->where('absensi_id', $absensi->id)
            ->where('tgl_izin', $byDate)
            ->get();

        return view('kehadiran.izin', [
            "title" => "Data Karyawan Izin",
            "absensi" => $absensi,
            "izins" => $izins,
            "date" => $byDate
        ]);
    }

    public function presentUser(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|numeric',
            "tgl_hadir" => "required|date"
        ]);

        $user = User::findOrFail($validated['user_id']);

        $kehadiran = Kehadiran::query()
            ->where('absensi_id', $absensi->id)
            ->where('user_id', $user->id)
            ->where('tgl_hadir', $validated['tgl_hadir'])
            ->first();

        // jika data user yang didapatkan dari request user_id, tgl_hadir, sudah absen atau sudah ada ditable presences
        if ($kehadiran || !$user)
            return back()->with('failed', 'Request tidak diterima.');

        Kehadiran::create([
            "absensi_id" => $absensi->id,
            "user_id" => $user->id,
            "tgl_hadir" => $validated['tgl_hadir'],
            "absen_masuk" => now()->toTimeString(),
            "absen_keluar" => now()->toTimeString()
        ]);

        return back()
            ->with('success', "Berhasil menyimpan data hadir atas nama \"$user->name\".");
    }

    public function acceptPermission(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|numeric',
            "tgl_izin" => "required|date"
        ]);

        $user = User::findOrFail($validated['user_id']);

        $izin = Izin::query()
            ->where('absensi_id', $absensi->id)
            ->where('user_id', $user->id)
            ->where('tgl_izin', $validated['tgl_izin'])
            ->first();

        $kehadiran = Kehadiran::query()
            ->where('absensi_id', $absensi->id)
            ->where('user_id', $user->id)
            ->where('tgl_hadir', $validated['tgl_izin'])
            ->first();

        // jika data user yang didapatkan dari request user_id, presence_date, sudah absen atau sudah ada ditable presences
        if ($kehadiran || !$user)
            return back()->with('failed', 'Request tidak diterima.');

        Kehadiran::create([
            "absensi_id" => $absensi->id,
            "user_id" => $user->id,
            "tgl_hadir" => $validated['tgl_izin'],
            "absen_masuk" => now()->toTimeString(),
            "absen_keluar" => now()->toTimeString(),
            'izin' => true
        ]);

        $izin->update([
            'status' => 1
        ]);

        return back()
            ->with('success', "Berhasil menerima data izin karyawan atas nama \"$user->name\".");
    }

    private function getNotPresentEmployees($kehadirans)
    {
        $uniquePresenceDates = $kehadirans->unique("tgl_hadir")->pluck('tgl_hadir');
        $uniquePresenceDatesAndCompactTheUserIds = $uniquePresenceDates->map(function ($date) use ($kehadirans) {
            return [
                "tgl_hadir" => $date,
                "user_ids" => $kehadirans->where('tgl_hadir', $date)->pluck('user_id')->toArray()
            ];
        });
        $notPresentData = [];
        foreach ($uniquePresenceDatesAndCompactTheUserIds as $kehadiran) {
            $notPresentData[] =
                [
                    "not_presence_date" => $kehadiran['tgl_hadir'],
                    "users" => User::query()
                        ->onlyEmployees()
                        ->whereNotIn('id', $kehadiran['user_ids'])
                        ->get()
                        ->toArray()
                ];
        }
        return $notPresentData;
    }
}
