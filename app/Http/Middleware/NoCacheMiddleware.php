<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class NoCacheMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Jika response berupa file download, jangan ubah header-nya
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        // Tambahkan header no-cache hanya untuk response biasa (HTML/JSON)
        return $response
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
