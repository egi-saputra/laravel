<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Skip logging untuk truncate
        if ($request->routeIs('visitor.truncate')) {
            return $next($request);
        }

        // Catat jika user membuka halaman login ("/")
        if ($request->is('/') && !session()->has('login_page_visited')) {
            Visitor::create([
                'user_id'    => null, // belum login
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            session(['login_page_visited' => true]);
        }

        // Catat jika user baru saja login
        if (auth()->check() && !session()->has('user_logged_recorded')) {
            Visitor::create([
                'user_id'    => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            session(['user_logged_recorded' => true]);
        }

        return $next($request);
    }
}
