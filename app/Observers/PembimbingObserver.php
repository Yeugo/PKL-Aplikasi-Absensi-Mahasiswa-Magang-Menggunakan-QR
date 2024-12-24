<?php

namespace App\Observers;

use App\Models\Pembimbing;
use App\Models\User;

class PembimbingObserver
{
    public function deleted(Pembimbing $pembimbing)
    {
        if ($pembimbing->user_id) {
            User::where('id', $pembimbing->user_id)->delete();
        }
    }
}
