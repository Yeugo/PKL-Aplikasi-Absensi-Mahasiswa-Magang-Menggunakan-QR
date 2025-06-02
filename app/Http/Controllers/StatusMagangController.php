<?php

namespace App\Http\Controllers;

use App\Models\Peserta; // Ganti dengan path model Peserta Anda
use Illuminate\Http\Request;

class StatusMagangController extends Controller
{
    public function index()
    {
        return view('status_magang.index', [
            "title" => "Data Status Magang"
        ]);
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        $peserta = Peserta::query()->whereIn('id', $ids)->get();

        return view('status_magang.edit', [
            "title" => "Edit Data Status Magang",
            "peserta" => $peserta
        ]);
    }
}