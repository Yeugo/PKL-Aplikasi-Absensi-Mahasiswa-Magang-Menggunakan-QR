<?php

namespace App\Http\Controllers;

use App\Models\Pembimbing;
use Illuminate\Http\Request;

class PembimbingController extends Controller
{
    public function index()
    {
        return view('pembimbing.index', [
            "title" => "Data Peserta Magang"
        ]);
    }

    public function create()
    {
        return view('pembimbing.create',[
            "title" => "Data Pembimbing Magang"
        ]);
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        $pembimbing = Pembimbing::query()->whereIn('id', $ids)->get();

        return view('pembimbing.edit', [
            "title" => "Edit Data Pembimbing Magang",
            "pembimbing" => $pembimbing
        ]);
    }
}
