<?php

namespace App\Http\Controllers;

use App\Models\Peserta; // Ganti dengan path model Peserta Anda
use Illuminate\Http\Request;

class StatusMagangController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua peserta atau filter berdasarkan request
        $query = Peserta::query()->with(['pembimbing', 'bidang']); // Eager load relasi jika perlu

        // Contoh filter berdasarkan status (jika ada input filter dari view)
        if ($request->has('status_filter') && $request->status_filter != '') {
            $query->where('status_penyelesaian', $request->status_filter);
        }

        // Contoh filter berdasarkan periode (jika ada input filter dari view)
        // Anda perlu logika filter tanggal yang lebih kompleks di sini jika diperlukan

        $pesertaMagang = $query->orderBy('name', 'asc')->paginate(15); // Paginasi

        $possibleStatuses = [
            'Belum Dimulai',
            'Aktif',
            'Selesai',
            'Diberhentikan',
            'Mengundurkan Diri',
        ];


        return view('status_magang.index', [ // Sesuaikan path view Anda
            'pesertaMagang' => $pesertaMagang,
            'possibleStatuses' => $possibleStatuses,
            'selectedStatus' => $request->status_filter ?? '',
            "title" => "Status Peserta Magang",
        ]);
    }

    // Method untuk menampilkan form edit status (opsional, bisa digabung di form edit peserta)
    public function editStatus(Peserta $peserta) // Route model binding
    {
        $possibleStatuses = [
            'Belum Dimulai',
            'Aktif',
            'Selesai',
            'Diberhentikan',
            'Mengundurkan Diri',
        ];
        return view('status_magang.edit', compact('peserta', 'possibleStatuses'))->with("title", "Status Peserta Magang"); // Sesuaikan path
    }

    // Method untuk update status
    public function updateStatus(Request $request, Peserta $peserta) // Route model binding
    {
        $request->validate([
            'tgl_mulai_magang' => 'nullable|date',
            'tgl_selesai_magang_rencana' => 'nullable|date|after_or_equal:tgl_mulai_magang',
            'status_penyelesaian' => 'required|string|in:Belum Dimulai,Aktif,Selesai,Diberhentikan,Mengundurkan Diri',
            'tanggal_penyelesaian_aktual' => 'nullable|date|required_if:status_penyelesaian,Selesai', // Wajib jika status "Selesai"
            'keterangan_status' => 'nullable|string|max:1000',
        ]);

        $dataToUpdate = [
            'tgl_mulai_magang' => $request->tgl_mulai_magang,
            'tgl_selesai_magang_rencana' => $request->tgl_selesai_magang_rencana,
            'status_penyelesaian' => $request->status_penyelesaian,
            'keterangan_status' => $request->keterangan_status,
        ];

        if ($request->status_penyelesaian == 'Selesai') {
            $dataToUpdate['tanggal_penyelesaian_aktual'] = $request->tanggal_penyelesaian_aktual ?: now()->toDateString();
        } else {
            $dataToUpdate['tanggal_penyelesaian_aktual'] = null; // Kosongkan jika tidak selesai
        }

        $peserta->update($dataToUpdate);

        return redirect()->route('status_magang.index')->with('success', 'Status magang peserta berhasil diperbarui.'); // Sesuaikan nama rute
    }
}