<?php
// namespace App\Http\Livewire;

// use App\Models\Pendaftaran;
// use Livewire\Component;
// use Livewire\WithFileUploads;

// class PendaftaranCreateForm extends Component
// {
//     use WithFileUploads;

//     public $name, $npm, $phone, $univ, $alamat, $email, $bidang_id, $surat_pengantar, $dokumen_lain;

//     protected $rules = [
//         'name' => 'required',
//         'npm' => 'required|unique:pendaftarans,npm',
//         'email' => 'required|email|unique:pendaftarans,email',
//         'phone' => 'required|unique:pendaftarans,phone',
//         'univ' => 'required',
//         'alamat' => 'required',
//         'bidang_id' => 'required|exists:bidangs,id',
//         'surat_pengantar' => 'required|file|mimes:pdf|max:2048',
//         'dokumen_lain' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', 
//     ];
    

    

//     public function save()
//     {
//         $this->validate([
//             'name' => 'required',
//             'npm' => 'required|unique:pendaftarans,npm',
//             'email' => 'required|email|unique:pendaftarans,email',
//             'phone' => 'required|unique:pendaftarans,phone',
//             'univ' => 'required',
//             'alamat' => 'required',
//             'bidang_id' => 'required|exists:bidangs,id',
//             'surat_pengantar' => 'required|file|mimes:pdf|max:2048',
//             'dokumen_lain' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048'
//         ]);

//         $suratPath = $this->surat_pengantar->storeAs('surat_pengantar', $this->npm . '_surat_pengantar.pdf', 'public');
//         $dokumenPath = $this->dokumen_lain ? $this->dokumen_lain->storeAs('foto_pendaftar', $this->npm . '_foto.' . $this->dokumen_lain->getClientOriginalExtension(), 'public') : null;

//         Pendaftaran::create([
//             'name' => $this->name,
//             'npm' => $this->npm,
//             'phone' => $this->phone,
//             'univ' => $this->univ,
//             'alamat' => $this->alamat,
//             'email' => $this->email,
//             'bidang_id' => $this->bidang_id,
//             'surat_pengantar' => $suratPath,
//             'dokumen_lain' => $dokumenPath,
//         ]);

        

//         session()->flash('success', 'Pendaftaran berhasil.');
//         return redirect()->route('pendaftaran.create');
//     }

//     public function render()
//     {
//         return view('livewire.pendaftaran-create-form', [
//             'bidangs' => \App\Models\Bidang::all(),
//         ]);
//     }
// }

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Bidang;
use Livewire\Component;
use App\Models\Pendaftaran;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class PendaftaranCreateForm extends Component
{
    use WithFileUploads;

    public $name;
    public $npm;
    public $email;
    public $phone;
    public $univ;
    public $jenis_kelamin;
    public $alamat;
    public $bidang_id;
    public $tgl_mulai_magang;
    public $tgl_selesai_magang_rencana;
    public $status_penyelesaian = 'Belum Dimulai'; // Default status
    public $surat_pengantar;
    public $dokumen_lain;

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'npm' => 'required|unique:pendaftarans,npm',
            'email' => 'required|email|unique:pendaftarans,email|unique:users,email',
            'phone' => 'required|unique:pendaftarans,phone',
            'univ' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'bidang_id' => 'required|exists:bidangs,id',
            'tgl_mulai_magang' => 'required|date',
            'tgl_selesai_magang_rencana' => 'required|date|after_or_equal:tgl_mulai_magang',
            'surat_pengantar' => 'required|file|mimes:pdf|max:2048',
            'dokumen_lain' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Ubah mimes untuk foto
        ]);

        $suratPath = $this->surat_pengantar->storeAs('surat_pengantar', $this->npm . '_surat_pengantar.pdf', 'public');
        $dokumenPath = $this->dokumen_lain ? $this->dokumen_lain->storeAs('foto_pendaftar', $this->npm . '_foto.' . $this->dokumen_lain->getClientOriginalExtension(), 'public') : null;

        Pendaftaran::create([
            'name' => $this->name,
            'npm' => $this->npm,
            'phone' => $this->phone,
            'univ' => $this->univ,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat' => $this->alamat,
            'email' => $this->email,
            'bidang_id' => $this->bidang_id,
            'tgl_mulai_magang' => $this->tgl_mulai_magang,
            'tgl_selesai_magang_rencana' => $this->tgl_selesai_magang_rencana,
            'status_penyelesaian' => 'Belum Dimulai',
            'surat_pengantar' => $suratPath,
            'dokumen_lain' => $dokumenPath,
        ]);

        session()->flash('success', 'Pendaftaran Berhasil, Silahkan Cek Email Secara Berkala Untuk Info Selanjutnya');
        return redirect()->route('pendaftaran.create');
    }

    public function render()
    {
        return view('livewire.pendaftaran-create-form', [
            'bidangs' => \App\Models\Bidang::all(),
        ]);
    }
}