<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use App\Models\Pembimbing;
use App\Models\Peserta;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent,};

final class PembimbingTable extends PowerGridComponent
{
    use ActionButton;

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'bulkCheckedDelete',
                'bulkCheckedEdit',
                'exportToPDF'
            ]
        );
    }

    public function header(): array
    {
        return [
            Button::add('bulk-checked')
                ->caption(__('Hapus'))
                ->class('btn btn-danger border-0')
                ->emit('bulkCheckedDelete', []),
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

    public function bulkCheckedDelete()
    {
        if (auth()->check()) {
            $ids = $this->checkedValues();

            if (!$ids)
                return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin dihapus terlebih dahulu.']);

            try {
                // Peserta::whereIn('id', $ids)->delete();
                foreach ($ids as $id) {
                    $pembimbing = Pembimbing::find($id);
                    if ($pembimbing) {
                        $pembimbing->delete();  // Akan memanggil observer deleted()
                    }
                }
                $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => 'Data Pembimbing berhasil dihapus.']);
            } catch (\Illuminate\Database\QueryException $ex) {
                $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Data gagal dihapus, kemungkinan ada data lain yang menggunakan data tersebut.']);
            }
        }
    }

    public function bulkCheckedEdit()
    {
        if (auth()->check()) {
            $ids = $this->checkedValues();

            if (!$ids)
                return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin diedit terlebih dahulu.']);

            $ids = join('-', $ids);
            // return redirect(route('department.edit', ['ids' => $ids])); // tidak berfungsi/menredirect
            return $this->dispatchBrowserEvent('redirect', ['url' => route('pembimbing.edit', ['ids' => $ids])]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
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

        $selectedData = Pembimbing::whereIn('id', $this->checkedValues())
        ->get();

        $pdf = Pdf::loadView('exports.PembimbingPdf', compact('selectedData', 'base64Image'))
        ->setPaper('a4', 'potrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'ExportPembimbing.pdf'
        );
        
    }

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Pembimbing>
     */
    public function datasource(): Builder
    {
        // return Pembimbing::query()
        //     ->join('bidangs', 'peserta.bidang_id', '=', 'bidangs.id')
        //     ->select('peserta.*', 'bidangs.name as bidang');
        return Pembimbing::with(['peserta', 'bidang'])->select('pembimbing.*');
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

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('nip')
            ->addColumn('phone')
            ->addColumn('alamat')
            // ->addColumn('bidang', function (Pembimbing $model) {
            //     return ucfirst($model->bidang);
            // })
            ->addColumn('bidang', function (Pembimbing $model) {
                return $model->bidang->name ?? 'No Bidang'; // Mengambil nama bidang dari relasi
            })
            ->addColumn('foto', function (Pembimbing $model) {
                // Cek apakah foto ada, jika ada tampilkan dalam bentuk link
                return $model->foto 
                    ? '<a href="' . asset('storage/' . $model->foto) . '" target="_blank">
                        <img src="' . asset('storage/' . $model->foto) . '" alt="Foto Peserta" width="20" height="20" class="d-block mx-auto">
                    </a>'
                    : 'No photo';
            })
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (Pembimbing $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'pembimbing.id')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name', 'pembimbing.name')
                ->searchable()
                ->makeInputText()
                ->editOnClick()
                ->sortable(),

            Column::make('NIP', 'nip', 'pembimbing.nip')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('No. Telp', 'phone', 'pembimbing.phone')
                ->searchable()
                ->makeInputText()
                ->sortable(),


            Column::make('Alamat', 'alamat', 'pembimbing.alamat')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Bidang', 'bidang')
                ->searchable()
                ->makeInputMultiSelect(Bidang::all(), 'name', 'bidang_id')
                ->sortable(),

            Column::make('Peserta', 'peserta_names')
                ->searchable()
                ->sortable(),

            Column::make('Foto', 'foto')
                ->searchable()
                ->sortable(),

            // Column::make('Created at', 'created_at', 'peserta.created_at')
            //     ->hidden(),

            Column::make('Created at', 'created_at_formatted', 'pembimbing.created_at')
                ->makeInputDatePicker()
                ->searchable()
        ];
    }
}
