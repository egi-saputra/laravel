<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavBotController extends Controller
{
    public function index()
    {
        // kembalikan view blade nav-bot (misal resources/views/components/nav-bot.blade.php)
        return view('components.nav-bot');
    }
}
