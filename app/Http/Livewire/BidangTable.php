<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Bidang;
use App\Models\Peserta;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent,};

final class BidangTable extends PowerGridComponent
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
                Bidang::whereIn('id', $ids)->delete();
                $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => 'Data jabatan berhasi dihapus.']);
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
            return $this->dispatchBrowserEvent('redirect', ['url' => route('bidangs.edit', ['ids' => $ids])]);
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
            $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin diekspor terlebih dahulu.']);
            return;
        }

        // Ambil data bidang berdasarkan ID yang dipilih
        $selectedData = \App\Models\Bidang::whereIn('id', $selectedIds)->get();

        // Ambil daftar tahun dinamis (5 tahun terakhir atau lebih)
        $tahunList = \App\Models\Peserta::selectRaw('DISTINCT YEAR(created_at) as tahun')
            ->whereIn('peserta_bidang_id', $selectedIds)
            ->orderBy('tahun', 'asc')
            ->pluck('tahun');

        // Hitung jumlah peserta per tahun untuk setiap bidang
        $selectedData = $selectedData->map(function ($bidang) use ($tahunList) {
            $jumlahPesertaPerTahun = [];
        
            // Tambahkan jumlah peserta saat ini sebagai kolom pertama
            $jumlahPesertaPerTahun['jumlah_saat_ini'] = $bidang->jumlah_peserta;
        
            // Hitung jumlah peserta per tahun
            foreach ($tahunList as $tahun) {
                $jumlahPesertaPerTahun[$tahun] = \App\Models\Peserta::where('peserta_bidang_id', $bidang->id)
                    ->whereYear('created_at', $tahun)
                    ->count();
            }
        
            $bidang->jumlah_peserta_per_tahun = $jumlahPesertaPerTahun;
            return $bidang;
        });

        // Generate PDF menggunakan view Blade
        $pdf = Pdf::loadView('exports.BidangPdf', compact('selectedData', 'tahunList'))
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'ExportBidang.pdf'
        );
    }




    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Bidang>
     */
    public function datasource(): Builder
    {
        return Bidang::query()
            ->withCount('peserta');
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

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
        // return PowerGrid::eloquent()
        //     ->addColumn('id')
        //     ->addColumn('name')
        //     ->addColumn('kepala_bidang')
        //     ->addColumn('jumlah_peserta')
        //     ->addColumn('created_at')
        //     ->addColumn('created_at_formatted', fn (Bidang $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
        $tahunList = DB::table('peserta')
        ->selectRaw('YEAR(created_at) as tahun')
        ->groupBy('tahun')
        ->orderBy('tahun')
        ->pluck('tahun');

        $powerGrid = PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('kepala_bidang')
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (Bidang $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
            // ->addColumn('jumlah_peserta');
        foreach ($tahunList as $tahun) {
            $powerGrid->addColumn("jumlah_peserta_$tahun", function (Bidang $model) use ($tahun) {
                return DB::table('peserta')
                    ->where('peserta_bidang_id', $model->id)
                    ->whereYear('created_at', $tahun)
                    ->count();
            });
        }

        return $powerGrid;
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        // Ambil 5 tahun terakhir
        $tahunList = DB::table('peserta')
        ->selectRaw('YEAR(created_at) as tahun')
        ->whereRaw('YEAR(created_at) >= YEAR(NOW()) - 5') // Hanya ambil 5 tahun terakhir
        ->groupBy('tahun')
        ->orderBy('tahun')
        ->pluck('tahun');

        // Array untuk menyimpan kolom
        $columns = [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Kepala Bidang', 'kepala_bidang')
                ->searchable()
                ->sortable(),

            // Column::make('Jumlah Peserta (Saat ini)', 'jumlah_peserta')
            //     ->searchable()
            //     ->sortable()
            //     ->bodyAttribute('text-center'),

            // Column::make('Created at', 'created_at')
            //     ->hidden(),

            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->makeInputDatePicker()
            //     ->searchable()
        ];

        // Loop untuk menambahkan kolom jumlah peserta berdasarkan tahun
        foreach ($tahunList as $tahun) {
            $columns[] = Column::make("Jumlah Peserta ($tahun)", "jumlah_peserta_$tahun")
                ->sortable("jumlah_peserta_$tahun")
                ->bodyAttribute('text-center');
        }

        return $columns;
    
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid Position Action Buttons.
     *
     * @return array<int, Button>
     */

    public function actions(): array
    {
        return [
            // Button::make('edit', 'Edit')
            //     ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
            //     ->route('position.edit', ['position' => 'id']),

            // Button::make('destroy', 'Delete')
            //     ->class('badge text-bg-danger border-0')
            //     ->route('positions.destroy', ['position' => 'id'])
            //     ->method('delete')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid Position Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($position) => $position->id === 1)
                ->hide(),
        ];
    }
    */
}
