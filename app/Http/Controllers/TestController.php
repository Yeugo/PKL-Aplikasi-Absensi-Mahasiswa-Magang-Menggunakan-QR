<?php
namespace App\Http\Controllers;


class TestController extends Controller
{
    public function test()
    {
        return view('pdf.surat_balasan', [
            "title" => "Pendaftaran Magang"
        ]);
    }

    public function create()
    {
        return view('pendaftaran.create',[
            "title" => "Tambah Data pendaftaran Magang"
        ]);
    }
}