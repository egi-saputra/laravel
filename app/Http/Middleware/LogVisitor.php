<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Skip logging untuk route truncate
        if ($request->routeIs('visitor.truncate')) {
            return $next($request);
        }

        // Catat hanya kalau user login DAN di halaman utama ("/")
        if (auth()->check() && $request->is('/')) {
            // Kalau belum pernah dihitung di sesi ini
            if (!session()->has('visitor_logged')) {
                Visitor::create([
                    'user_id'    => auth()->id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Tandai di session supaya tidak dihitung lagi
                session(['visitor_logged' => true]);
            }
        }

        return $next($request);
    }
}
