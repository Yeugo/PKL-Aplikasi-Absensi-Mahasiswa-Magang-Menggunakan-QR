<?php

namespace App\Http\Livewire;

use App\Models\Pembimbing;
use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\Peserta;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent,};

final class KegiatanTable extends PowerGridComponent
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
                $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => 'Data kegiatan berhasil dihapus.']);
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
            return $this->dispatchBrowserEvent('redirect', ['url' => route('kegiatan.edit', ['ids' => $ids])]);
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

        $selectedData = Kegiatan::whereIn('id', $this->checkedValues())
        ->get();

        $pdf = Pdf::loadView('exports.KegiatanPdf', compact('selectedData'))
        ->setPaper('a4', 'potrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'ExportKegiatan.pdf'
        );
        
    }

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Kegiatan>
     */
    public function datasource(): Builder
    {
        return Kegiatan::query()
            ->join('peserta', 'kegiatan.peserta_id', '=', 'peserta.id' )
            ->join('bidangs', 'peserta.peserta_bidang_id', '=', 'bidangs.id' )
            ->select('kegiatan.*', 'peserta.name as pesertaname', 'peserta.peserta_bidang_id', 'bidangs.name as bidang');
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
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('judul')
            ->addColumn('tgl_kegiatan')
            ->addColumn('waktu_mulai')
            ->addColumn('waktu_selesai')
            ->addColumn('bidang')
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (Kegiatan $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'kegiatan.id')
                ->searchable()
                ->sortable(),

            Column::make('Name', 'pesertaname')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Kegiatan', 'judul')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Deskripsi', 'deskripsi')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Tanggal Kegiatan', 'tgl_kegiatan', 'kegiatan.tgl_kegiatan')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Waktu Mulai', 'waktu_mulai', 'kegiatan.waktu_mulai')
                ->searchable()
                ->makeInputText()
                ->sortable(),
            
            Column::make('Waktu Selesai', 'waktu_selesai', 'kegiatan.waktu_selesai')
                ->searchable()
                ->makeInputText()
                ->sortable(),
            
            Column::make('Bidang', 'bidang')
                ->searchable()
                ->makeInputMultiSelect(Bidang::all(), 'name', 'peserta_bidang_id')
                ->sortable(),

            Column::make('Created at', 'created_at', 'kegiatan.created_at')
                ->hidden(),

            Column::make('Created at', 'created_at_formatted', 'kegiatan.created_at')
                ->makeInputDatePicker()
                ->searchable()
        ];
    }
}
