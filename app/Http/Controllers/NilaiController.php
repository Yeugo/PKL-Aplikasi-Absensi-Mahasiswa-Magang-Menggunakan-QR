<?php

namespace App\Http\Controllers;

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

}
