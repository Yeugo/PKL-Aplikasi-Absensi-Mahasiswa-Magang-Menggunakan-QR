<?php
// filepath: /c:/laragon/www/absensi-mhs-magang/app/Http/Controllers/PendaftaranController.php
namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

class PendaftaranController extends Controller
{
    public function index()
    {
        return view('pendaftaran.index', [
            "title" => "Pendaftaran Magang"
        ]);
    }

    public function create()
    {
        return view('pendaftaran.create',[
            "title" => "Tambah Data pendaftaran Magang"
        ]);
    }

    public function approve($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        $password = Hash::make('defaultpassword'); // Ganti dengan logika password yang sesuai

        $user = User::create([
            'email' => $pendaftaran->email,
            'password' => $password,
            'role_id' => 2,
        ]);

        $user->peserta()->create([
            'name' => $pendaftaran->name,
            'npm' => $pendaftaran->npm,
            'phone' => $pendaftaran->phone,
            'univ' => $pendaftaran->univ,
            'alamat' => $pendaftaran->alamat,
            'peserta_bidang_id' => $pendaftaran->bidang_id,
        ]);

        Mail::to($pendaftaran->email)->send(new UserCreated($user, $pendaftaran, ""));

        $pendaftaran->delete();

        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran diterima dan peserta telah dibuat.');
    }

    public function reject($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->delete();

        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran ditolak.');
    }
}