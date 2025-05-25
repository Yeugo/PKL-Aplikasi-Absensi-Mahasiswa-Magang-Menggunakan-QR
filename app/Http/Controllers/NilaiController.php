<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        return view('nilai.index', [
            "title" => "Data Nilai Peserta Magang"
        ]);
    }

    public function create()
    {
        return view('nilai.create', [
            "title" => "Data Nilai Peserta Magang"
        ]);
    }

    public function show($peserta_id)
    {
        $peserta = Peserta::with('nilai','pembimbing')->findOrFail($peserta_id);

        return view('nilai.show', [
            'title' => 'Detail Penilaian Kinerja Peserta Magang',
            'peserta' => $peserta,
            'nilai' => $peserta->nilai,
            'pembimbing' => $peserta->pembimbing,
        ]);
    }

}
