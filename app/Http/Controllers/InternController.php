<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mpdf;

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
            "title" => "Edit Data Anak Magang",
            "interns" => $interns
        ]);
    }

    // public function printPdf()
    // {
    //     // Mengambil data yang ingin dicetak
    //     $interns = User::all();

    //     // Load view yang akan di convert
    //     $html = view('interns.pdf', compact('interns'))->render();

    //     // Inisialisasi mPDF
    //     $mpdf = new Mpdf();

    //     // Tulis HTML ke dalam PDF
    //     $mpdf->WriteHTML($html);

    //     // Output ke browser (langsung download atau tampilkan)
    //     return $mpdf->Output('employee-report.pdf', 'I'); // 'I' untuk display di browser, 'D' untuk download langsung


    // }
}
