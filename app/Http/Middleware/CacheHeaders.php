<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  int  $ttl  waktu cache dalam detik (default: 3600 = 1 jam)
     */
    public function handle(Request $request, Closure $next, $ttl = 3600): Response
    {
        $response = $next($request);

        // Jika ada flash session (pesan alert, error, dsb) -> jangan cache
        if (
            session()->has('alert') ||
            session()->has('success') ||
            session()->has('error') ||
            session()->has('question') ||
            session()->has('warning')
        ) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        } else {
            // Cache sesuai TTL
            $response->headers->set('Cache-Control', "public, max-age={$ttl}, immutable");
            $response->headers->set('Pragma', 'cache');
            $response->headers->set(
                'Expires',
                gmdate('D, d M Y H:i:s \G\M\T', time() + $ttl)
            );
        }

        return $response;
    }
}
