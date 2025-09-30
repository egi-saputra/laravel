<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visitor;

class DevController extends Controller
{
    public function dashboard()
    {
        return view('dev.dashboard', [
            'pageTitle' => 'Developer Dashboard',
            'totalUsers' => User::count(),
            'onlineUsers' => User::where('last_activity', '>=', now()->subMinutes(5))->get(),
            'uniqueVisitors' => Visitor::distinct('ip_address')->count('ip_address'),
            'totalVisitors' => Visitor::count(),
        ]);
    }
}
