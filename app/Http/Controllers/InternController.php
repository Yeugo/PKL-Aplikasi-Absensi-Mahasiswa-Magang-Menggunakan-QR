<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class InternController extends Controller
{
    public function index()
    {
        return view('interns.index', [
            "title" => "Intern"
        ]);
    }

    public function create()
    {
        return view('interns.create', [
            "title" => "Tambah Data Anak Magang"
        ]);
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        // ambil data user yang hanya memiliki User::USER_ROLE_ID / role untuk karyawaan
        $interns = User::query()
            ->whereIn('id', $ids)
            ->get();

        return view('interns.edit', [
            "title" => "Edit Data Peserta Magang",
            "interns" => $interns
        ]);
    }
}
