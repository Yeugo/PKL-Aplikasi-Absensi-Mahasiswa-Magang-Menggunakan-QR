<?php

namespace App\Http\Livewire;

use App\Http\Traits\useUniqueValidation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class InternEditForm extends Component
{
    use useUniqueValidation;

    public $interns;
    public Collection $roles;

    public function mount(Collection $interns)
    {
        $this->interns = []; // reset, karena ada data interns sebelumnya

        foreach ($interns as $intern) {
            $this->interns[] = [
                'id' => $intern->id,
                'name' => $intern->name,
                'email' => $intern->email,
                'original_email' => $intern->email, // untuk cek validasi unique
                'phone' => $intern->phone,
                'original_phone' => $intern->phone, // untuk cek validasi unique nanti
                'role_id' => $intern->role_id,
            ];
        }
        $this->roles = Role::all();
    }
    public function saveInterns()
    {
        $roleIdRuleIn = join(',', $this->roles->pluck('id')->toArray());
        $positionIdRuleIn = join(',', $this->positions->pluck('id')->toArray());

        $this->validate([
            'interns.*.name' => 'required',
            'interns.*.email' => 'required|email',
            'interns.*.phone' => 'required',
            'interns.*.password' => '',
            'interns.*.role_id' => 'required|in:' . $roleIdRuleIn,
        ]);

        if (!$this->isUniqueOnLocal('phone', $this->interns)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input No. Telp tidak mangandung nilai yang sama dengan input lainnya.');
        }

        if (!$this->isUniqueOnLocal('email', $this->interns)) {
            $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
            return session()->flash('failed', 'Pastikan input Email tidak mangandung nilai yang sama dengan input lainnya.');
        }

        // alasan menggunakan create alih2 mengunakan ::insert adalah karena tidak looping untuk menambahkan created_at dan updated_at
        $affected = 0;
        foreach ($this->interns as $intern) {
            // cek unique validasi
            $internBeforeUpdated = User::find($intern['id']);

            if (!$this->isUniqueOnDatabase($internBeforeUpdated, $intern, 'phone', User::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "No. Telp dari data peserta magang {$intern['id']} sudah terdaftar. Silahkan masukan email yang berbeda!");
            }

            if (!$this->isUniqueOnDatabase($internBeforeUpdated, $intern, 'email', User::class)) {
                $this->dispatchBrowserEvent('livewire-scroll', ['top' => 0]);
                return session()->flash('failed', "Email dari data peserta Magang {$intern['id']} sudah terdaftar. Silahkan masukan email yang berbeda!");
            }

            $affected += $internBeforeUpdated->update([
                'name' => $intern['name'],
                'email' => $intern['email'],
                'phone' => $intern['phone'],
                'role_id' => $intern['role_id'],
            ]);
        }

        $message = $affected === 0 ?
            "Tidak ada data karyawaan yang diubah." :
            "Ada $affected data karyawaan yang berhasil diedit.";

        return redirect()->route('interns.index')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.intern-edit-form');
    }
}
