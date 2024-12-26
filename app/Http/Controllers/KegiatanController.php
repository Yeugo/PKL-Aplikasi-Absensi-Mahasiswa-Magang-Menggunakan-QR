<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Peserta;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        return view('kegiatan.index', [
            "title" => "Data Kegiatan Peserta Magang"
        ]);
    }

    public function indexPeserta()
    {
        // Ambil semua kegiatan yang terkait dengan pengguna yang sedang login
        $kegiatans = Kegiatan::where('peserta_id', auth()->user()->peserta->id)->get();

        $kegiatans->map(function ($kegiatan) {
            $kegiatan->tgl_kegiatan = Carbon::parse($kegiatan->tgl_kegiatan)->format('d M Y');
            return $kegiatan;
        });
    

        return view('home.kegiatanPeserta', ["title" => "Data Kegiatan Peserta Magang"], compact('kegiatans'));
    }

    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'tgl_kegiatan' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:kegiatan.*.waktu_mulai',
        ]);

        // Ambil peserta_id dari tabel peserta berdasarkan user_id
        $peserta = Peserta::where('user_id', auth()->id())->first();

        // Pastikan peserta ditemukan
        if (!$peserta) {
            return redirect()->back()->with('error', 'Peserta tidak ditemukan!');
        }

        // Simpan data ke database
        Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tgl_kegiatan' => $request->tgl_kegiatan,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'peserta_id' => $peserta->id, // Pastikan kegiatan dikaitkan dengan user yang sedang login
        ]);

        return redirect()->back()->with('success', 'Kegiatan berhasil ditambahkan!');
        dd($request->all());
    }

    public function destroy($id)
    {
        // Cari kegiatan berdasarkan ID
        $peserta = Peserta::where('user_id', auth()->id())->firstOrFail();

        $kegiatan = Kegiatan::where('id', $id)
        ->where('peserta_id', $peserta->id) // Menggunakan peserta_id
        ->firstOrFail();

        // Hapus kegiatan
        $kegiatan->delete();

        return redirect()->back()->with('success', 'Kegiatan berhasil dihapus!');
    }


    

    public function create()
    {
        return view('kegiatan.create', [
            "title" => "Tambah Kegiatan Peserta Magang"
        ]);
        
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        $kegiatan = Kegiatan::query()->whereIn('id', $ids)->get();

        return view('kegiatan.edit', [
            "title" => "Edit Data Kegiatan",
            "kegiatan" => $kegiatan
        ]);
    }
}
