<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class GuruController extends Controller
{
    // Dashboard guru
    public function dashboard()
    {
        return view('guru.dashboard');
    }

}
