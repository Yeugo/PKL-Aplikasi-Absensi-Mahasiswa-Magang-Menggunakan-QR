<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            "title" => "User"
        ]);
    }

    public function create()
    {
        return view('users.create', [
            "title" => "Tambah Data User"
        ]);
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        // ambil data user yang hanya memiliki User::USER_ROLE_ID / role untuk karyawaan
        $users = User::query()
            ->whereIn('id', $ids)
            ->get();

        return view('users.edit', [
            "title" => "Edit Data User",
            "users" => $users
        ]);
    }
}
