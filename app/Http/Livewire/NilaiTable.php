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

final class NilaiTable extends PowerGridComponent
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
            ],
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
                Kegiatan::query()->whereIn('id', $ids)->delete();
                return $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => 'Data berhasil dihapus.']);
            } catch (QueryException $e) {
                return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Gagal menghapus data.']);
            }
        }
    }

    public function bulkCheckedEdit()
    {
        if (auth()->check()) {
            $ids = $this->checkedValues();

            if (!$ids)
                return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin diedit terlebih dahulu.']);

            return redirect()->route('kegiatan.edit', ['ids' => implode('-', $ids)]);
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
        if (auth()->check()) {
            $ids = $this->checkedValues();

            if (!$ids)
                return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin dicetak terlebih dahulu.']);

            $kegiatan = Kegiatan::query()->whereIn('id', $ids)->get();

            $pdf = Pdf::loadView('kegiatan.export', [
                'kegiatan' => $kegiatan,
                'title' => 'Data Kegiatan Peserta Magang',
            ]);

            return $pdf->download('data-kegiatan-peserta-magang.pdf');
        }
    }

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Nilai>
     */
    public function datasource(): Builder
    {
        $pembimbing = Pembimbing::where('user_id', auth()->id())->first();

        return Peserta::query()
            ->where('pembimbing_id', $pembimbing->id)
            ->with(['user', 'nilai']);
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
        $pembimbingId = Pembimbing::where('user_id', auth()->id())->value('id');

        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('status', function ($row) use ($pembimbingId) {
                $sudahDinilai = \App\Models\Nilai::where('peserta_id', $row->id)
                    ->where('pembimbing_id', $pembimbingId)
                    ->exists();

                return $sudahDinilai ? 'Sudah Dinilai' : 'Belum Dinilai';
        });
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
                ->sortable(),

            Column::make('Status', 'status')
                ->searchable()
                ->makeInputText()
                ->sortable(),
        ];
    }

    public function actionRules(): array
    {
        return [                
            Rule::button('test')
                ->when(fn($peserta) => $peserta->nilai)
                ->hide()

            // Rule::checkbox()
            //     ->when(fn ($dish) => $dish->in_stock == false)
            //     ->hide(),
                
            // Rule::rows()
            //     ->when(fn ($dish) => $dish->in_stock == false)
            //     ->setAttribute('class', '!bg-red-200'),
        ];
    }

    /**
     * PowerGrid Attendance Action Buttons.
     *
     * @return array<int, Button>
     */

    public function actions(): array
    {
        return [
            Button::make('test', 'Tes Tombol')
                ->class('badge text-bg-primary')
        ];
    }
}