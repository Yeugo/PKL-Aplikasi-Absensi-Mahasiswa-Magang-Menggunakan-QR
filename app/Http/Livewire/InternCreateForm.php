<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class InternCreateForm extends Component
{
    public $interns;
    public Collection $roles;
    public Collection $bidangs;

    public function mount()
    {
        $this->roles = Role::all();
        $this->bidangs = Bidang::all();
        $this->interns = [
            ['name' => '', 
             'email' => '',
             'password' => '',  
             'phone' => '', 
             'role_id' => User::USER_ROLE_ID, 
             'bidang_id' => $this->bidangs->first()->id]
        ];
    }

    public function addInternInput(): void
    {
        $this->interns[] = ['name' => '', 'email' => '', 'password' => '', 'phone' => '', 'role_id' => User::USER_ROLE_ID, 'bidang_id' => $this->bidangs->first()->id];
    }

    public function removeInternInput(int $index): void
    {
        unset($this->interns[$index]);
        $this->interns = array_values($this->interns);
    }

    public function saveInterns()
    {
        // cara lebih cepat, dan kemungkinan data role tidak akan diubah/ditambah
        $roleIdRuleIn = join(',', $this->roles->pluck('id')->toArray());
        $bidangIdRuleIn = join(',', $this->bidangs->pluck('id')->toArray());
        // $roleIdRuleIn = join(',', Role::all()->pluck('id')->toArray());

        // setidaknya input pertama yang hanya required,
        // karena nanti akan difilter apakah input kedua dan input selanjutnya apakah berisi
        $this->validate([
            'interns.*.name' => 'required',
            'interns.*.email' => 'required|email|unique:users,email',
            'interns.*.password' => '',
            'interns.*.phone' => 'required|unique:users,phone',
            'interns.*.role_id' => 'required|in:' . $roleIdRuleIn,
            'interns.*.bidang_id' => 'required|in:' . $bidangIdRuleIn,
        ]);
        // cek apakah no. telp yang diinput unique
        $phoneNumbers = array_map(function ($intern) {
            return trim($intern['phone']);
        }, $this->interns);
        $uniquePhoneNumbers = array_unique($phoneNumbers);

        if (count($phoneNumbers) != count($uniquePhoneNumbers)) {
            // layar browser ke paling atas agar user melihat alert error
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        foreach ($this->interns as $intern) {
            if (trim($intern['password']) === '') $intern['password'] = '123';
            $intern['password'] = Hash::make($intern['password']);
            User::create($intern);
            $affected++;
        }

        redirect()->route('interns.index')->with('success', "Ada ($affected) data karyawaan yang berhasil ditambahkan.");
    }

    public function render()
    {
        return view('livewire.intern-create-form');
    }
}
