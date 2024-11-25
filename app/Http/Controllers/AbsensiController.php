<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        return view('absensi.index', [
            "title" => "Data Absensi"
        ]); 
    }

    public function create()
    {
        return view('absensi.create', [
            "title" => "Tambah Data Absensi"
        ]);
    }

    public function edit()
    {
        return view('absensi.edit', [
            "title" => "Edit Data Absensi",
            "absensi" => Absensi::findOrfail(request('id'))
        ]);
    }
}
