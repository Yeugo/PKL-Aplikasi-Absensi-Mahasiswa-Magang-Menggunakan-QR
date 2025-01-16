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

    public function mount(Collection $users)
    {
        $this->users = []; // reset, karena ada data interns sebelumnya

        foreach ($users as $user) {
            $this->users[] = [
                'id' => $user->id,
                'email' => $user->email,
                'original_email' => $user->email, // untuk cek validasi unique
                'role_id' => $user->role_id,
            ];
        }
        $this->roles = Role::all();
    }
    public function saveUsers()
    {
        $roleIdRuleIn = join(',', $this->roles->pluck('id')->toArray());

        $this->validate([
            'users.*.email' => 'required|email',
            'users.*.password' => '',
            'users.*.role_id' => 'required|in:' . $roleIdRuleIn,
        ]);

        if (!$this->isUniqueOnLocal('email', $this->users)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input Email tidak mangandung nilai yang sama dengan input lainnya.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        foreach ($this->users as $user) {
            // cek unique validasi
            $userBeforeUpdated = User::find($user['id']);

            if (!$this->isUniqueOnDatabase($userBeforeUpdated, $user, 'email', User::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "Email dari data peserta Magang {$user['id']} sudah terdaftar. Silahkan masukan email yang berbeda!");
            }

            $affected += $userBeforeUpdated->update([
                'email' => $user['email'],
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
