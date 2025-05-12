<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Izin;
use App\Models\Absensi;
use App\Models\Holiday;
use App\Models\Peserta;
use App\Models\Presence;
use Carbon\CarbonPeriod;
use App\Models\Kehadiran;
use App\Models\Attendance;
use App\Models\Permission;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $absensis = Absensi::query()
            // ->with('positions')
            // ->forCurrentUser(auth()->user()->position_id)
            ->get()
            ->sortByDesc('data.is_end')
            ->sortByDesc('data.is_start');

        $kehadiranCount = Kehadiran::where('user_id', auth()->id())
            ->whereBetween('tgl_hadir', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();

        $peserta = Peserta::find(auth()->user()->peserta->id);

        

        return view('home.index', [
            "title" => "Absensi",
            "absensis" => $absensis,
            "peserta" => $peserta,
            "kehadiranCount" => $kehadiranCount
        ]);


    }

    public function show(Absensi $absensi)
    {
        $kehadirans = Kehadiran::query()
            ->where('absensi_id', $absensi->id)
            ->where('user_id', auth()->user()->id)
            ->get();

        $isHasEnterToday = $kehadirans
            ->where('tgl_hadir', now()->toDateString())
            ->isNotEmpty();

        $isTherePermission = Izin::query()
            ->where('tgl_izin', now()->toDateString())
            ->where('absensi_id', $absensi->id)
            ->where('user_id', auth()->user()->id)
            ->first();

        $data = [
            'is_has_enter_today' => $isHasEnterToday, // sudah absen masuk
            'is_not_out_yet' => $kehadirans->where('absen_keluar', null)->isNotEmpty(), // belum absen pulang
            'is_there_permission' => (bool) $isTherePermission,
            'is_permission_accepted' => $isTherePermission?->status ?? false
        ];

        $holiday = $absensi->data->is_holiday_today ? Holiday::query()
            ->where('holiday_date', now()->toDateString())
            ->first() : false;

        $history = Kehadiran::query()
            ->where('user_id', auth()->user()->id)
            ->where('absensi_id', $absensi->id)
            ->get();

        // untuk melihat karyawan yang tidak hadir
        $priodDate = CarbonPeriod::create($absensi->created_at->toDateString(), now()->toDateString())
            ->toArray();

        foreach ($priodDate as $i => $date) { // get only stringdate
            $priodDate[$i] = $date->toDateString();
        }

        $priodDate = array_slice(array_reverse($priodDate), 0, 30);

        return view('home.show', [
            "title" => "Informasi Absensi Kehadiran",
            "absensi" => $absensi,
            "data" => $data,
            "holiday" => $holiday,
            'history' => $history,
            'priodDate' => $priodDate
        ]);
    }

    public function permission(Absensi $absensi)
    {
        return view('home.permission', [
            "title" => "Form Permintaan Izin",
            "absensi" => $absensi
        ]);
    }

    // for qrcode
    public function sendEnterPresenceUsingQRCode()
    {
        $code = request('code');
        $absensi = Absensi::query()->where('code', $code)->first();

        if ($absensi && $absensi->data->is_start && $absensi->data->is_using_qrcode) { // sama (harus) dengan view
            // fix: user bisa absensi dengan tanggal yang sama, cek apakah user id attendance id dan presence date sudah ada
            Kehadiran::create([
                "user_id" => auth()->user()->id,
                "absensi_id" => $absensi->id,
                "tgl_hadir" => now()->toDateString(),
                "absen_masuk" => now()->toTimeString(),
                "absen_keluar" => null
            ]);

            return response()->json([
                "success" => true,
                "message" => "Kehadiran atas nama '" . auth()->user()->name . "' berhasil dikirim."
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "Terjadi masalah pada saat melakukan absensi."
        ], 400);
    }

    public function sendOutPresenceUsingQRCode()
    {
        $code = request('code');
        $absensi = Absensi::query()->where('code', $code)->first();

        if (!$absensi)
            return response()->json([
                "success" => false,
                "message" => "Terjadi masalah pada saat melakukan absensi."
            ], 400);

        // jika absensi sudah jam pulang (is_end) dan tidak menggunakan qrcode (kebalikan)
        if (!$absensi->data->is_end && !$absensi->data->is_using_qrcode) // sama (harus) dengan view
            return false;

        $kehadiran = Kehadiran::query()
            ->where('user_id', auth()->user()->id)
            ->where('absensi_id', $absensi->id)
            ->where('tgl_hadir', now()->toDateString())
            ->where('absen_keluar', null)
            ->first();

        if (!$kehadiran) // hanya untuk sekedar keamanan (kemungkinan)
            return response()->json([
                "success" => false,
                "message" => "Terjadi masalah pada saat melakukan absensi."
            ], 400);

        // untuk refresh if statement
        $this->data['is_not_out_yet'] = false;
        $kehadiran->update(['absen_keluar' => now()->toTimeString()]);

        return response()->json([
            "success" => true,
            "message" => "Atas nama '" . auth()->user()->name . "' berhasil melakukan absensi pulang."
        ]);
    }

    public function kegiatan()
    {
        return view('home.kegiatanPeserta', [
            "title" => "Data Kegiatan Peserta Magang"
        ]);
    }
}
