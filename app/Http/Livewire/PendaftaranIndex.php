<?php
// filepath: /c:/laragon/www/absensi-mhs-magang/app/Http/Livewire/PendaftaranIndex.php
namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Bidang;
use App\Models\Peserta;
use App\Mail\UserCreated;
use App\Models\Pembimbing;
use App\Models\Pendaftaran;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\PendaftaranRejected;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent,};

final class PendaftaranIndex extends PowerGridComponent
{
    use ActionButton;

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'approveSelected',
                'rejectSelected',
                'exportToPDF'
            ]
        );
    }

    public function header(): array
    {
        return [
            Button::add('approve-checked')
                ->caption(__('Terima'))
                ->class('btn btn-success border-0')
                ->emit('approveSelected', []),
            Button::add('reject-checked')
                ->caption(__('Tolak'))
                ->class('btn btn-danger border-0')
                ->emit('rejectSelected', []),
            Button::add('exportPDF')
                ->caption(__('Cetak'))
                ->class('btn btn-secondary border-0')
                ->emit('exportToPDF', []),
        ];
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

    public function approveSelected()
    {
        $ids = $this->checkedValues();

        foreach ($ids as $id) {
            $pendaftaran = Pendaftaran::findOrFail($id);

            // Tetapkan password default
            $randomPassword = Str::random(12);

            // Enkripsi password default
            $password = Hash::make($randomPassword);

            $user = User::create([
                'email' => $pendaftaran->email,
                'password' => $password,
                'role_id' => 2,
            ]);

            $user->peserta()->create([
                'name' => $pendaftaran->name,
                'npm' => $pendaftaran->npm,
                'phone' => $pendaftaran->phone,
                'univ' => $pendaftaran->univ,
                'alamat' => $pendaftaran->alamat,
                'peserta_bidang_id' => $pendaftaran->bidang_id,
                'tgl_mulai_magang' => $pendaftaran->tgl_mulai_magang,
                'tgl_selesai_magang_rencana' => $pendaftaran->tgl_selesai_magang_rencana,
                'status_penyelesaian' => $pendaftaran->status_penyelesaian,
            ]);

            Mail::to($pendaftaran->email)->send(new UserCreated($user, $pendaftaran, $randomPassword, $pendaftaran));

            $pendaftaran->delete();
        }

        $this->dispatchBrowserEvent('showToast', [
        'success' => true,
        'message' => 'Pendaftaran yang dipilih telah diterima dan peserta telah dibuat.'
    ]);
    }

    public function rejectSelected()
    {
        $ids = $this->checkedValues();

        foreach ($ids as $id) {
            $pendaftaran = Pendaftaran::findOrFail($id);

            // Kirim email penolakan
            Mail::to($pendaftaran->email)->send(new PendaftaranRejected($pendaftaran));

            $pendaftaran->delete();
        }

        session()->flash('success', 'Pendaftaran yang dipilih telah ditolak.');
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

        $selectedData = Pendaftaran::whereIn('id', $this->checkedValues())
        ->get();

        $pdf = Pdf::loadView('exports.PendaftaranPdf', compact('selectedData', 'base64Image'))
        ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'ExportPendaftaran.pdf'
        );
        
    }

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Pendaftaran>
     */
    public function datasource(): Builder
    {
        return Pendaftaran::query()
            ->join('bidangs', 'pendaftarans.bidang_id', '=', 'bidangs.id')
            ->select('pendaftarans.*','bidangs.name as bidang');
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
            ->addColumn('npm')
            ->addColumn('email')
            ->addColumn('phone')
            ->addColumn('univ')
            ->addColumn('jenis_kelamin')
            ->addColumn('alamat')
            ->addColumn('bidang', function (Pendaftaran $model) {
                return ucfirst($model->bidang);
            })
            ->addColumn('surat_pengantar', function (Pendaftaran $model) {
                return $model->surat_pengantar 
                    ? '<a href="' . asset('storage/' . $model->surat_pengantar) . '" target="_blank">
                        Lihat Surat
                    </a>'
                    : 'No photo';
            })
            ->addColumn('dokumen_lain', function (Pendaftaran $model) {
                // Cek apakah dokumen_lain ada, jika ada tampilkan dalam bentuk link
                return $model->dokumen_lain 
                    ? '<a href="' . asset('storage/' . $model->dokumen_lain) . '" target="_blank">
                        <img src="' . asset('storage/' . $model->dokumen_lain) . '" alt="Foto Pendaftaran" width="50" height="50">
                    </a>'
                    : 'No photo';
            })
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (Pendaftaran $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id', 'pendaftarans.id')
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

            Column::make('Email', 'email', 'pendaftaran.email')
                ->searchable()
                ->makeInputText()
                ->sortable()
                ->hidden(),

            Column::make('No. Telp', 'phone', 'peserta.phone')
                ->searchable()
                ->makeInputText()
                ->sortable()
                ->hidden(),

            Column::make('Universitas', 'univ', 'peserta.univ')
                ->searchable()
                ->makeInputText()
                ->sortable(),
            
            Column::make('Jenis Kelamin', 'jenis_kelamin', 'pendaftaran.jenis_kelamin')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Alamat', 'alamat', 'peserta.alamat')
                ->searchable()
                ->makeInputText()
                ->sortable(),

            Column::make('Bidang', 'bidang')
                ->searchable()
                ->makeInputText()
                ->sortable(),
            
            Column::make('Tanggal Mulai Magang', 'tgl_mulai_magang', 'pendaftaran.tgl_mulai_magang')
                ->bodyAttribute('text-center')
                ->searchable()
                ->makeInputDatePicker()
                ->sortable(),

            Column::make('Tanggal Selesai Magang', 'tgl_selesai_magang_rencana', 'pendaftaran.tgl_selesai_magang_rencana')
                ->bodyAttribute('text-center')
                ->searchable()
                ->makeInputDatePicker()
                ->sortable(),

            Column::make('Surat Pengantar', 'surat_pengantar')
                ->searchable()
                ->sortable(),

            Column::make('Foto', 'dokumen_lain')
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