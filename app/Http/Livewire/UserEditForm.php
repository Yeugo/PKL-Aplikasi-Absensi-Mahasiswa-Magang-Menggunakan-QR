<?php

namespace App\Http\Livewire;

use App\Http\Traits\useUniqueValidation;
use App\Models\Bidang;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserEditForm extends Component
{
    use useUniqueValidation;

    public $users;
    public Collection $roles;
    public Collection $bidangs;

    public function mount(Collection $users)
    {
        $this->users = []; // reset, karena ada data interns sebelumnya

        foreach ($users as $user) {
            $this->users[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'original_email' => $user->email, // untuk cek validasi unique
                'phone' => $user->phone,
                'original_phone' => $user->phone, // untuk cek validasi unique nanti
                'bidang_id' => $user->bidang_id,
                'role_id' => $user->role_id,
            ];
        }
        $this->roles = Role::all();
        $this->bidangs =Bidang::all();
    }
    public function saveUsers()
    {
        $roleIdRuleIn = join(',', $this->roles->pluck('id')->toArray());
        $bidangIdRuleIn = join(',', $this->bidangs->pluck('id')->toArray());

        $this->validate([
            'users.*.name' => 'required',
            'users.*.email' => 'required|email',
            'users.*.phone' => 'required',
            'users.*.password' => '',
            'users.*.bidang_id' => 'required|in:' . $bidangIdRuleIn,
            'users.*.role_id' => 'required|in:' . $roleIdRuleIn,
        ]);

        if (!$this->isUniqueOnLocal('phone', $this->users)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama dengan input lainnya.');
        }

        if (!$this->isUniqueOnLocal('email', $this->users)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input Email tidak mangandung nilai yang sama dengan input lainnya.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        foreach ($this->users as $user) {
            // cek unique validasi
            $userBeforeUpdated = User::find($user['id']);

            if (!$this->isUniqueOnDatabase($userBeforeUpdated, $user, 'phone', User::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "No. Telp dari data user {$user['id']} sudah terdaftar. Silahkan masukan email yang berbeda!");
            }

            if (!$this->isUniqueOnDatabase($userBeforeUpdated, $user, 'email', User::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "Email dari data peserta Magang {$user['id']} sudah terdaftar. Silahkan masukan email yang berbeda!");
            }

            $affected += $userBeforeUpdated->update([
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'bidang_id' => $user['bidang_id'],
                'role_id' => $user['role_id'],
            ]);
        }

        $message = $affected === 0 ?
            "Tidak ada data user yang diubah." :
            "Ada $affected data user yang berhasil diedit.";

        return redirect()->route('users.index')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.user-edit-form');
    }
}
