<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index()
    {
        return view('peserta.index', [
            "title" => "Data Peserta Magang"
        ]);
    }

    public function create()
    {
        return view('peserta.create', [
            "title" => "Tambah Data Peserta Magang"
        ]);
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        $peserta = Peserta::query()->whereIn('id', $ids)->get();

        return view('peserta.edit', [
            "title" => "Edit Data Peserta Magang",
            "peserta" => $peserta
        ]);
    }
}
