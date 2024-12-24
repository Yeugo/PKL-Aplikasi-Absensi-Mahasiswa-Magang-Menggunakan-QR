<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Position;
use App\Models\Kehadiran;
use App\Models\Department;
use App\Models\Pembimbing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $kehadiranCountLast7Days = \App\Models\Kehadiran::whereBetween('created_at', [
            Carbon::now()->subDays(7), // 7 hari yang lalu
            Carbon::now() // hari ini
        ])->count();

        return view('dashboard.index', [
            "title" => "Dashboard",
            "userCount" => User::count(),
            "pesertaCount" => Peserta::count(),
            "pembimbingCount" => Pembimbing::count(),
            "kehadiranCount" => Kehadiran::count(),
            "kehadiranPerminggu" => $kehadiranCountLast7Days
        ]);
    }
}
