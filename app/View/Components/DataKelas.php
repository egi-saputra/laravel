<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class DataKelas extends Component
{
    public $kelas;
    public $guru;

    public function __construct($kelas, $guru)
    {
        $this->kelas = $kelas;
        $this->guru  = $guru;
    }

    public function render(): View|Closure|string
    {
        return view('components.data-kelas');
    }
}
