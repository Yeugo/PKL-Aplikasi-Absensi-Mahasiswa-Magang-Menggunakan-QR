<?php

namespace App\Http\Livewire;

use App\Models\Nilai;
use App\Models\Bidang;
use App\Models\Peserta;
use App\Models\Kegiatan;
use App\Models\Pembimbing;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent,};

final class StatusMagangTable extends PowerGridComponent
{
    use ActionButton;

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'bulkCheckedDelete',
                'bulkCheckedEdit',
                'exportToPDF',
                // 'nilaiClicked',
                // 'detailClicked',
            ],
        );
    }

    public function header(): array
    {
        return [
            // Button::add('bulk-checked')
            //     ->caption(__('Hapus'))
            //     ->class('btn btn-danger border-0')
            //     ->emit('bulkCheckedDelete', []),
            Button::add('bulk-edit-checked')
                ->caption(__('Edit'))
                ->class('btn btn-success border-0')
                ->emit('bulkCheckedEdit', []),
            Button::add('exportPDF')
                ->caption(__('Cetak'))
                ->class('btn btn-secondary border-0')
                ->emit('exportToPDF', []),
        ];
    }

    // public function bulkCheckedDelete()
    // {
    //     if (auth()->check()) {
    //         $ids = $this->checkedValues();

    //         if (!$ids)
    //             return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin dihapus terlebih dahulu.']);

    //         try {
    //             Kegiatan::query()->whereIn('id', $ids)->delete();
    //             return $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => 'Data berhasil dihapus.']);
    //         } catch (QueryException $e) {
    //             return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Gagal menghapus data.']);
    //         }
    //     }
    // }

    public function bulkCheckedEdit()
    {
        if (auth()->check()) {
            $ids = $this->checkedValues();

            if (!$ids)
                return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin diedit terlebih dahulu.']);

            return redirect()->route('status_magang.edit', ['ids' => implode('-', $ids)]);
        }
    }
    
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function exportToPDF()
    {
        $selectedIds = $this->checkedValues();

        if (empty($selectedIds)) {
            $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin di export terlebih dahulu. ']);
            return;
        }

        // --- Bagian Baru untuk Gambar Base64 ---
        $imagePath = public_path('storage/assets/logobjm.png'); // Jalur fisik ke gambar Anda
        $base64Image = ''; // Inisialisasi variabel

        if (file_exists($imagePath)) {
            $imageData = file_get_contents($imagePath); // Baca isi file gambar
            $imageType = pathinfo($imagePath, PATHINFO_EXTENSION); // Dapatkan ekstensi file (png)
            $base64Image = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
        } else {
            // Opsional: Log pesan error jika gambar tidak ditemukan
            //
        }
        // --- Akhir Bagian Baru ---

        $selectedData = Peserta::whereIn('id', $this->checkedValues())
        ->get();

        $pdf = Pdf::loadView('exports.StatusMagangPdf', compact('selectedData', 'base64Image'))
        ->setPaper('a4', 'potrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'ExportStatusMagang.pdf'
        );
        
    }

    
    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Peserta>
     */
    public function datasource(): Builder
    {
        // return Peserta::query()
        //     ->join('bidangs', 'peserta.peserta_bidang_id', '=', 'bidangs.id')
        //     ->leftjoin('pembimbing', 'peserta.pembimbing_id', '=', 'pembimbing.id')
        //     ->select('peserta.*', 'pembimbing.name as pembimbingname','bidangs.name as bidang');
        return Peserta::query()->with(['pembimbing', 'bidang']);
    }

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridEloquent
    {
        // $pembimbingId = Pembimbing::where('user_id', auth()->id())->value('id');

        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('npm')
            ->addColumn('univ')
            ->addColumn('tgl_mulai_magang')
            ->addColumn('tgl_selesai_magang_rencana')
            ->addColumn('status_penyelesaian')
            ->addColumn("status_penyelesaian", fn (Peserta $model) => match ($model->status_penyelesaian) {
                'Belum Dimulai' => '<span class="badge text-bg-secondary">Belum Dimulai</span>',
                'Aktif' => '<span class="badge text-bg-info">Aktif</span>',
                'Selesai' => '<span class="badge text-bg-success">Selesai</span>',
                'Diberhentikan' => '<span class="badge text-bg-danger">Diberhentikan</span>',
                'Mengundurkan Diri' => '<span class="badge text-bg-warning">Mengundurkan Diri</span>',
                default => '<span class="badge text-bg-light">' . e($model->status_penyelesaian) . '</span>',
            })
            ->addColumn('tanggal_penyelesaian_aktual');
    }

     /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Nama', 'name', 'peserta.name')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('NPM', 'npm', 'peserta.npm')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            // Column::make('Universitas', 'univ', 'peserta.univ')
            //     ->searchable()
            //     ->makeInputText()
            //     ->sortable(),

            Column::make('Tanggal Mulai', 'tgl_mulai_magang', 'peserta.tgl_mulai_magang')
                ->bodyAttribute('text-center')
                ->searchable()
                ->makeInputDatePicker()
                ->sortable(),

            Column::make('Tanggal Selesai (Rencana)', 'tgl_selesai_magang_rencana')
                ->bodyAttribute('text-center')
                ->searchable()
                ->makeInputDatePicker()
                ->sortable(),

            Column::make('Status Penyelesaian', 'status_penyelesaian')
                ->bodyAttribute('text-center')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Tanggal Selesai Aktual', 'tanggal_penyelesaian_aktual')
                ->bodyAttribute('text-center')
                ->searchable()
                ->makeInputDatePicker()
                ->sortable(),
        ];
    }

    // public function actionRules(): array
    // {
    //     return [                
    //         Rule::button('nilai')
    //             ->when(fn($peserta) => $peserta->nilai)
    //             ->disable(),
            
    //         Rule::button('detail')
    //             ->when(fn($peserta) => !$peserta->nilai)
    //             ->disable(),
    //     ];
    // }

    /**
     * PowerGrid Attendance Action Buttons.
     *
     * @return array<int, Button>
     */

    // public function actions(): array
    // {
    //     return [
    //         Button::make('nilai', 'Nilai')
    //             ->class('btn btn-success btn-sm text-center')
    //             ->emit('nilaiClicked', ['peserta_id' => 'id']),
    //         Button::make('detail', 'Detail')
    //             ->class('btn btn-info btn-sm')
    //             ->emit('detailClicked', ['peserta_id' => 'id']),
    //     ];
    // }
}
