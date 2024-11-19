<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Bidang;

class UserObserver
{
    public function created(User $user)
    {
        // Cek jika user memiliki bidang
        if ($user->bidang) {
            // Update jumlah peserta di bidang terkait
            $user->bidang->update([
                'jumlah_peserta' => $user->bidang->users()->count() // Hitung jumlah user yang ada
            ]);
        }
    }

    public function deleted(User $user)
    {
        if ($user->bidang_id) {
            Bidang::where('id', $user->bidang_id)->decrement('jumlah_peserta');
        }
    }

    public function updated(User $user)
    {
        if ($user->isDirty('bidang_id')) {
            // Decrement jumlah_peserta di bidang lama
            if ($user->getOriginal('bidang_id')) {
                Bidang::where('id', $user->getOriginal('bidang_id'))->decrement('jumlah_peserta');
            }

            // Increment jumlah_peserta di bidang baru
            if ($user->bidang_id) {
                Bidang::where('id', $user->bidang_id)->increment('jumlah_peserta');
            }
        }
    }
}
