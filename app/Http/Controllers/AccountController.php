<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;


class AccountController extends Controller
{

    public function index()
    {
        return view('account.edit', [
            "title" => "Update Informasi Akun"
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::find(auth()->id()); // Mengambil data user yang sedang login

        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Menyimpan perubahan ke database

        return redirect()->route('account.index')->with('success', 'Informasi Account berhasil diperbarui!');
    }
}
