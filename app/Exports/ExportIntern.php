<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportIntern implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data; // Data yang dipilih untuk diekspor
    }

    public function headings(): array
    {
        return [
            'ID', 'Name', 'Email', 'Phone', 'Role', 'Bidang', 'Created At'
        ];
    }

    /**
     * Memetakan data sebelum diekspor, di sini kita format kolom tanggal.
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            ucfirst($user->role->name), // Ambil nama role yang terkait dengan ID
            ucfirst($user->bidang->name), // Ambil nama bidang yang terkait dengan ID
            Carbon::parse($user->created_at)->format('d/m/Y H:i:s') // Format tanggal sesuai kebutuhan
        ];
    }
}
