<?php

namespace App\Http\Livewire;

use App\Models\Bidang;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserCreateForm extends Component
{
    public $users;
    public Collection $roles;
    public Collection $bidangs;

    public function mount()
    {
        $this->roles = Role::all();
        $this->bidangs = Bidang::all();
        $this->users = [
            ['name' => '', 
             'email' => '',
             'password' => '',  
             'phone' => '', 
             'role_id' => User::USER_ROLE_ID, 
             'bidang_id' => $this->bidangs->first()->id]
        ];
    }

    public function addUserInput(): void
    {
        $this->users[] = ['name' => '', 'email' => '', 'password' => '', 'phone' => '', 'role_id' => User::USER_ROLE_ID, 'bidang_id' => $this->bidangs->first()->id];
    }

    public function removeUserInput(int $index): void
    {
        unset($this->users[$index]);
        $this->users = array_values($this->users);
    }

    public function saveUsers()
    {
        // cara lebih cepat, dan kemungkinan data role tidak akan diubah/ditambah
        $roleIdRuleIn = join(',', $this->roles->pluck('id')->toArray());
        $bidangIdRuleIn = join(',', $this->bidangs->pluck('id')->toArray());
        // $roleIdRuleIn = join(',', Role::all()->pluck('id')->toArray());

        // setidaknya input pertama yang hanya required,
        // karena nanti akan difilter apakah input kedua dan input selanjutnya apakah berisi
        $this->validate([
            'users.*.name' => 'required',
            'users.*.email' => 'required|email|unique:users,email',
            'users.*.password' => '',
            'users.*.phone' => 'required|unique:users,phone',
            'users.*.role_id' => 'required|in:' . $roleIdRuleIn,
            'users.*.bidang_id' => 'required|in:' . $bidangIdRuleIn,
        ]);
        // cek apakah no. telp yang diinput unique
        $phoneNumbers = array_map(function ($user) {
            return trim($user['phone']);
        }, $this->users);
        $uniquePhoneNumbers = array_unique($phoneNumbers);

        if (count($phoneNumbers) != count($uniquePhoneNumbers)) {
            // layar browser ke paling atas agar user melihat alert error
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        foreach ($this->users as $user) {
            if (trim($user['password']) === '') $user['password'] = '123';
            $user['password'] = Hash::make($user['password']);
            User::create($user);
            $affected++;
        }

        redirect()->route('users.index')->with('success', "Ada ($affected) data user yang berhasil ditambahkan.");
    }

    public function render()
    {
        return view('livewire.user-create-form');
    }
}
