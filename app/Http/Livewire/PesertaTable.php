<?php

namespace App\Http\Livewire;

use App\Models\User;
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

final class PesertaTable extends PowerGridComponent
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
                    $peserta = Peserta::find($id);
                    if ($peserta) {
                        $peserta->delete();  // Akan memanggil observer deleted()
                    }
                }
                $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => 'Data Peserta Magang berhasil dihapus.']);
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
            return $this->dispatchBrowserEvent('redirect', ['url' => route('peserta.edit', ['ids' => $ids])]);
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

        // $selectedData = Peserta::whereIn('id', $this->checkedValues())
        // ->get();

        // Mulai membangun query untuk data yang dipilih
        $query = Peserta::whereIn('id', $selectedIds);

        // Terapkan sorting dari PowerGrid
        // Untuk PowerGrid v5.x dan yang lebih baru, gunakan $this->sortField
        if (isset($this->sortField) && !empty($this->sortField) && isset($this->sortDirection)) {
            $sortField = $this->sortField;
            $sortDirection = $this->sortDirection;
            $query->orderBy($sortField, $sortDirection);
        }
        // Untuk versi PowerGrid yang lebih lama, mungkin menggunakan $this->sortField dan $this->sortDirection
        // elseif (isset($this->sortField) && !empty($this->sortField) && isset($this->sortDirection)) {
        //     $query->orderBy($this->sortField, $this->sortDirection);
        // }
        else {
            // Fallback jika tidak ada sorting aktif di PowerGrid (misalnya, urutkan berdasarkan ID)
            $query->orderBy('id', 'asc');
        }

        $selectedData = $query->get();

        $pdf = Pdf::loadView('exports.PesertaPdf', compact('selectedData', 'base64Image'))
        ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'ExportPeserta.pdf'
        );
        
    }

    public $showModal = false;
    public $modalFoto = '';

    public function showModal($foto)
    {
        $this->modalFoto = $foto;
        $this->showModal = true; // Menampilkan modal
    }

    public function closeModal()
    {
        $this->showModal = false; // Menutup modal
    }


    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Peserta>
     */
    public function datasource(): Builder
    {
        return Peserta::query()
            ->join('bidangs', 'peserta.peserta_bidang_id', '=', 'bidangs.id')
            ->leftjoin('pembimbing', 'peserta.pembimbing_id', '=', 'pembimbing.id')
            ->select('peserta.*', 'pembimbing.name as pembimbingname','bidangs.name as bidang');
    }

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        

        return [
            'pembimbing' => [
                'name',
            ]
        ];
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
            ->addColumn('npm')
            ->addColumn('phone')
            ->addColumn('univ')
            ->addColumn('alamat')
            ->addColumn('bidang', function (Peserta $model) {
                return ucfirst($model->bidang);
            })
            ->addColumn('pembimbing', function (Peserta $model) {
                return ucfirst($model->pembimbingname ?? "Belum ada Pembimbing");
            })
            ->addColumn('foto', function (Peserta $model) {
                // Cek apakah foto ada, jika ada tampilkan dalam bentuk link
                return $model->foto 
                    ? '<a href="' . asset('storage/' . $model->foto) . '" target="_blank">
                        <img src="' . asset('storage/' . $model->foto) . '" alt="Foto Peserta" width="20" height="20" class="d-block mx-auto" >
                    </a>'
                    : 'No photo';
            })
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (Peserta $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'peserta.id')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name', 'peserta.name')
                ->searchable()
                ->makeInputText()
                ->editOnClick()
                ->sortable(),

            Column::make('NPM', 'npm', 'peserta.npm')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('No. Telp', 'phone', 'peserta.phone')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Universitas', 'univ', 'peserta.univ')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Alamat', 'alamat', 'peserta.alamat')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Bidang', 'bidang', 'bidangs.name')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Pembimbing', 'pembimbingname', 'pembimbing.name')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Foto', 'foto')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at', 'peserta.created_at')
                ->hidden(),

            Column::make('Created at', 'created_at_formatted', 'peserta.created_at')
                ->makeInputDatePicker()
                ->searchable()
        ];
    }
}
