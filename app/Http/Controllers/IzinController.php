<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function show()
    {
        $id = request('id');
        return Izin::findOrFail($id);
    }
}
