<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employees.index', [
            "title" => "Karyawaan"
        ]);
    }

    public function create()
    {
        return view('employees.create', [
            "title" => "Tambah Data Karyawaan"
        ]);
    }

    public function edit()
    {
        $ids = request('ids');
        if (!$ids)
            return redirect()->back();
        $ids = explode('-', $ids);

        // ambil data user yang hanya memiliki User::USER_ROLE_ID / role untuk karyawaan
        $employees = User::query()
            ->whereIn('id', $ids)
            ->get();

        return view('employees.edit', [
            "title" => "Edit Data Karyawaan",
            "employees" => $employees
        ]);
    }

    public function printPdf()
    {
        // Mengambil data yang ingin dicetak
        $employees = User::all();

        // Load view yang akan di convert
        $html = view('employees.pdf', compact('employees'))->render();

        // Inisialisasi mPDF
        $mpdf = new Mpdf();

        // Tulis HTML ke dalam PDF
        $mpdf->WriteHTML($html);

        // Output ke browser (langsung download atau tampilkan)
        return $mpdf->Output('employee-report.pdf', 'I'); // 'I' untuk display di browser, 'D' untuk download langsung


    }
}
