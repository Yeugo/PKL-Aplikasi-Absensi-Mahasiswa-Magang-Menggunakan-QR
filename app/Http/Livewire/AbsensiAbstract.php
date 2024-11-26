<?php

namespace App\Http\Livewire;

use App\Models\Position;
use Livewire\Component;

class AbsensiAbstract extends Component
{
    public $absensi;

    protected $rules = [
        'absensi.title' => 'required|string|min:6',
        'absensi.description' => 'required|string|max:500',
        'absensi.start_time' => 'required|date_format:H:i',
        'absensi.batas_start_time' => 'required|date_format:H:i|after:start_time',
        'absensi.end_time' => 'required|date_format:H:i',
        'absensi.batas_end_time' => 'required|date_format:H:i|after:end_time',
        'absensi.code' => 'sometimes|nullable|boolean',
    ];

}
