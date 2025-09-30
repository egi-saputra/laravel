<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UpdateLastActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Update last_activity setiap request
            $user->update([
                'last_activity' => Carbon::now(),
            ]);
        }

        return $next($request);
    }
}
