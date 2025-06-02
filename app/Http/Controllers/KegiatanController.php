<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Peserta;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        return view('kegiatan.index', [
            "title" => "Data Kegiatan Peserta Magang"
        ]);
    }

    public function create()
    {
        return view('kegiatan.create', [
            "title" => "Tambah Kegiatan Peserta Magang"
        ]);
        
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        $kegiatan = Kegiatan::query()->whereIn('id', $ids)->get();

        return view('kegiatan.edit', [
            "title" => "Edit Data Kegiatan",
            "kegiatan" => $kegiatan
        ]);
    }
}
