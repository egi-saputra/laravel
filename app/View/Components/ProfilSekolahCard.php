<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\ProfilSekolah;

class ProfilSekolahCard extends Component
{
    public $profil;

    public function __construct()
    {
        // Ambil data profil pertama
        $this->profil = ProfilSekolah::first();
    }

    public function render()
    {
        return view('components.profil-sekolah-card');
    }
}
