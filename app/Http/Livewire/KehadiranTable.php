<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use App\Models\Kehadiran;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class KehadiranTable extends PowerGridComponent
{
    use ActionButton;

    public $absensiId;
    public string $sortField = 'kehadiran.created_at';
    public string $sortDirection = 'desc';

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'exportToPDF'
            ]
        );
    }

    public function header(): array
    {
        return [
            Button::add('exportPDF')
                ->caption(__('Cetak'))
                ->class('btn btn-secondary border-0')
                ->emit('exportToPDF', []),
        ];
    }

    // Feature Setup
    public function setUp(): array
    {
        $this->showCheckBox();

        return[
            Header::make()->showSearchInput()->showToggleColumns(),
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

        // $selectedData = Kehadiran::whereIn('id', $this->checkedValues())
        // ->get();

        $selectedData = Kehadiran::whereIn('id', $this->checkedValues())
        ->get();

        $pdf = Pdf::loadView('exports.KehadiranPdf', compact('selectedData'))
        ->setPaper('a4', 'potrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'ExportKehadiran.pdf'
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
     * @return Builder<\App\Models\Kehadiran>
     */
    public function datasource(): Builder
    {
        return Kehadiran::query()
            // ->where('absensi_id', $this->absensiId)
            // ->join('users', 'kehadiran.user_id', '=', 'users.id')
            // ->select('kehadiran.*', 'users.name as user_name');
            ->where('absensi_id', $this->absensiId)
            ->join('users', 'kehadiran.user_id', '=', 'users.id')
            ->join('peserta', 'users.id', '=', 'peserta.user_id') // Relasi ke peserta
            ->join('bidangs', 'peserta.peserta_bidang_id', '=', 'bidangs.id' )
            ->select('kehadiran.*', 'peserta.name as peserta_name', 'bidangs.name as bidang'); // Ambil nama dari tabel peserta
            
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
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('user_name')
            ->addColumn('bidang')
            ->addColumn("tgl_hadir")
            ->addColumn("absen_masuk")
            ->addColumn("absen_keluar", fn (Kehadiran $model) => $model->absen_keluar ?? '<span class="badge text-bg-danger">Belum Absensi Pulang</span>')
            ->addColumn("izin", fn (Kehadiran $model) => $model->izin ?
                '<span class="badge text-bg-warning">Izin</span>' : '<span class="badge text-bg-success">Hadir</span>')
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (Kehadiran $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
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
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Nama', 'peserta_name')
                ->searchable()
                ->makeInputText('peserta.name')
                ->sortable(),

            Column::make('Bidang', 'bidang')
                ->searchable()
                ->makeInputMultiSelect(Bidang::all(), 'name', 'peserta_bidang_id')
                ->sortable(),

            Column::make('Tanggal Hadir', 'tgl_hadir')
                ->makeInputDatePicker()
                ->searchable()
                ->sortable(),

            Column::make('Jam Absen Masuk', 'absen_masuk')
                ->searchable()
                // ->makeInputRange('presence_enter_time') // terlalu banyak menggunakan bandwidth (ukuran data yang dikirim terlalu besar)
                ->makeInputText('presence_enter_time')
                ->sortable(),

            Column::make('Jam Absen Pulang', 'absen_keluar')
                ->searchable()
                // ->makeInputRange('presence_out_time') // ini juga
                ->makeInputText('presence_out_time')
                ->sortable(),

            Column::make('Status', 'izin')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->hidden(),

            Column::make('Created at', 'created_at_formatted')
                ->makeInputDatePicker()
                ->searchable()
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid Presence Action Buttons.
     *
     * @return array<int, Button>
     */

    /*
    public function actions(): array
    {
       return [
           Button::make('edit', 'Edit')
               ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
               ->route('presence.edit', ['presence' => 'id']),

           Button::make('destroy', 'Delete')
               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
               ->route('presence.destroy', ['presence' => 'id'])
               ->method('delete')
        ];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid Presence Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($presence) => $presence->id === 1)
                ->hide(),
        ];
    }
    */
}
