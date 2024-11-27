<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Izin;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class KehadiranController extends Controller
{
    public function index()
    {
        $absensi = Absensi::all()->sortByDesc('data.is_end')->sortByDesc('data.is_start');

        return view('kehadiran.index', [
            "title" => "Daftar Absensi Dengan Kehadiran",
            "absensi" => $absensi
        ]);
    }

    public function show(Absensi $absensi)
    {
        $absensi->load(['kehadiran']);

        return view('kehadiran.show', [
            "title" => "Data Detail Kehadiran",
            "absensi" => $absensi,
        ]);
    }

    public function showQrcode()
    {
        $code = request('code');
        $qrcode = $this->getQrCode($code);

        return view('kehadiran.qrcode', [
            "title" => "Generate Absensi QRCode",
            "qrcode" => $qrcode,
            "code" => $code
        ]);
    }
    
    //Note for Myself:D
    public function downloadQrCodePDF()
    {
        $code = request('code');
        $qrcode = $this->getQrCode($code);

        $html = '<img src="' . $qrcode . '" />';
        return Pdf::loadHTML($html)->setWarnings(false)->download('qrcode.pdf');
    }

    public function getQrCode(?string $code): string
    {
        if (!Kehadiran::query()->where('code', $code)->first())
            throw new NotFoundHttpException(message: "Tidak ditemukan absensi dengan code '$code'.");

        return parent::getQrCode($code);
    }

    public function notPresent(Absensi $absensi)
    {
        $byDate = now()->toDateString();
        if (request('display-by-date'))
            $byDate = request('display-by-date');

        $kehadiran = Kehadiran::query()
            ->where('absensi_id', $absensi->id)
            ->where('presence_date', $byDate)
            ->get(['presence_date', 'user_id']);

        // jika semua karyawan tidak hadir
        if ($kehadiran->isEmpty()) {
            $notPresentData[] =
                [
                    "not_presence_date" => $byDate,
                    "users" => User::query()
                        ->onlyEmployees()
                        ->get()
                        ->toArray()
                ];
        } else {
            $notPresentData = $this->getNotPresentEmployees($kehadiran);
        }


        return view('kehadiran.not-present', [
            "title" => "Data Karyawan Tidak Hadir",
            "absensi" => $absensi,
            "notPresentData" => $notPresentData
        ]);
    }

    public function izin(Absensi $absensi)
    {
        $byDate = now()->toDateString();
        if (request('display-by-date'))
            $byDate = request('display-by-date');

        $izin = Izin::query()
            ->where('absensi_id', $absensi->id)
            ->where('permission_date', $byDate)
            ->get();

        return view('presences.izin', [
            "title" => "Data Karyawan Izin",
            "absensi" => $absensi,
            "izin" => $izin,
            "date" => $byDate
        ]);
    }

    public function presentUser(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|numeric',
            "presence_date" => "required|date"
        ]);

        $user = User::findOrFail($validated['user_id']);

        $kehadiran = Kehadiran::query()
            ->where('absensi_id', $absensi->id)
            ->where('user_id', $user->id)
            ->where('presence_date', $validated['presence_date'])
            ->first();

        // jika data user yang didapatkan dari request user_id, presence_date, sudah absen atau sudah ada ditable presences
        if ($kehadiran || !$user)
            return back()->with('failed', 'Request tidak diterima.');

        Kehadiran::create([
            "absensi_id" => $absensi->id,
            "user_id" => $user->id,
            "presence_date" => $validated['presence_date'],
            "presence_enter_time" => now()->toTimeString(),
            "presence_out_time" => now()->toTimeString()
        ]);

        return back()
            ->with('success', "Berhasil menyimpan data hadir atas nama \"$user->name\".");
    }

    public function acceptPermission(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|numeric',
            "permission_date" => "required|date"
        ]);

        $user = User::findOrFail($validated['user_id']);

        $izin = Izin::query()
            ->where('absensi_id', $absensi->id)
            ->where('user_id', $user->id)
            ->where('permission_date', $validated['permission_date'])
            ->first();

        $kehadiran = Kehadiran::query()
            ->where('absensi_id', $absensi->id)
            ->where('user_id', $user->id)
            ->where('presence_date', $validated['permission_date'])
            ->first();

        // jika data user yang didapatkan dari request user_id, presence_date, sudah absen atau sudah ada ditable presences
        if ($kehadiran || !$user)
            return back()->with('failed', 'Request tidak diterima.');

        Kehadiran::create([
            "absensi_id" => $absensi->id,
            "user_id" => $user->id,
            "presence_date" => $validated['permission_date'],
            "presence_enter_time" => now()->toTimeString(),
            "presence_out_time" => now()->toTimeString(),
            'is_permission' => true
        ]);

        $izin->update([
            'is_accepted' => 1
        ]);

        return back()
            ->with('success', "Berhasil menerima data izin karyawan atas nama \"$user->name\".");
    }

    private function getNotPresentEmployees($kehadiran)
    {
        $uniquePresenceDates = $kehadiran->unique("presence_date")->pluck('presence_date');
        $uniquePresenceDatesAndCompactTheUserIds = $uniquePresenceDates->map(function ($date) use ($kehadiran) {
            return [
                "presence_date" => $date,
                "user_ids" => $kehadiran->where('presence_date', $date)->pluck('user_id')->toArray()
            ];
        });
        $notPresentData = [];
        foreach ($uniquePresenceDatesAndCompactTheUserIds as $presence) {
            $notPresentData[] =
                [
                    "not_presence_date" => $presence['presence_date'],
                    "users" => User::query()
                        ->onlyEmployees()
                        ->whereNotIn('id', $presence['user_ids'])
                        ->get()
                        ->toArray()
                ];
        }
        return $notPresentData;
    }
}
