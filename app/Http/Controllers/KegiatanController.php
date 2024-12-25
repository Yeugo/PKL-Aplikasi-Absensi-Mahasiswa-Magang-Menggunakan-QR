<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

    public function indexPeserta()
    {
        // Ambil semua kegiatan yang terkait dengan pengguna yang sedang login
        $kegiatans = Kegiatan::where('peserta_id', auth()->user()->peserta->id)->get();

        $kegiatans->map(function ($kegiatan) {
            $kegiatan->tgl_kegiatan = Carbon::parse($kegiatan->tgl_kegiatan)->format('d M Y');
            return $kegiatan;
        });
    

        return view('home.kegiatanPeserta', ["title" => "Data Kegiatan Peserta Magang"], compact('kegiatans'));
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
