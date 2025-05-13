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
}