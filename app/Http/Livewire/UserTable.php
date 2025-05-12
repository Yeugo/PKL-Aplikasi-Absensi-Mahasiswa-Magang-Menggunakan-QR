<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use App\Models\Bidang;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class UserTable extends PowerGridComponent
{
    use ActionButton;

    //Table sort field
    public string $sortField = 'users.created_at';
    public string $sortDirection = 'desc';

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
            // Button::add('exportPDF')
            //     ->caption(__('Cetak'))
            //     ->class('btn btn-secondary border-0')
            //     ->emit('exportToPDF', []),
        ];
    }

    

    public function bulkCheckedDelete()
    {
        if (auth()->check()) {
            $ids = $this->checkedValues();

            if (!$ids)
                return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Pilih data yang ingin dihapus terlebih dahulu.']);

            if (in_array(auth()->user()->id, $ids))
                return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => 'Anda tidak diizinkan untuk menghapus data yang sedang anda gunakan untuk login.']);


            try {
                User::whereIn('id', $ids)->delete();
                $this->dispatchBrowserEvent('showToast', ['success' => true, 'message' => 'Data user berhasil dihapus.']);
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
            return $this->dispatchBrowserEvent('redirect', ['url' => route('users.edit', ['ids' => $ids])]);
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
        
        $selectedData = User::with('bidang', 'role')
        ->whereIn('id', $this->checkedValues())
        ->get();
        
        $pdf = Pdf::loadView('exports.InternPdf', compact('selectedData'))
        ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'PesertaMagang.pdf'
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
     * @return Builder<\App\Models\User>
     */
    public function datasource(): Builder
    {
        return User::query()
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->leftJoin('peserta', 'users.id', '=', 'peserta.user_id')
        ->leftJoin('pembimbing', 'users.id', '=', 'pembimbing.user_id')
        ->select(
            'users.id as id',
            'users.email as user_email',
            'roles.name as role_name',
            'peserta.name as peserta_name',
            'pembimbing.name as pembimbing_name'
        );
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
            ->addColumn('user_email')
            ->addColumn('name', function ($user) {
                return $user->peserta_name ?? $user->pembimbing_name ?? 'Unknown';
            })
            ->addColumn('role_name', function ($row) {
                return Str::title($row->role_name); // Mengubah ke kapital
            })
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (User $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
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
            Column::make('ID', 'id', 'users.id')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->makeInputText()
                ->sortable(),
            
            Column::make('Email', 'user_email')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Role', 'role_name')
                ->searchable()
                ->makeInputMultiSelect(Role::all(), 'name', 'role_id')
                ->sortable(),

            Column::make('Created at', 'created_at', 'users.created_at')
                ->hidden(),

            Column::make('Created at', 'created_at_formatted', 'users.created_at')
                ->makeInputDatePicker()
                ->searchable()
        ];
    }
}
