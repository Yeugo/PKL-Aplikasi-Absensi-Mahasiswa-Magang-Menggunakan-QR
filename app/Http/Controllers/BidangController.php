<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidang;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;

class BidangController extends Controller
{
    public function index()
    {
        return view('bidangs.index', [
            "title" => "Bidang"
        ]);
    }

    public function create()
    {
        return view('bidangs.create', [
            "title" => "Tambah Data Bidang"
        ]);
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        $bidangs = Bidang::query()->whereIn('id', $ids)->get();

        return view('bidangs.edit', [
            "title" => "Edit Bidang",
            "bidangs" => $bidangs
        ]);
    }
}