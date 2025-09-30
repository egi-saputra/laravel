<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Reusable redirect with alert
    protected function alertRedirect($route, $type = 'success', $title = 'Berhasil!', $message = 'Operasi berhasil dilakukan.')
    {
        return redirect()->route($route)->with('alert', [
            'title' => $title,
            'message' => $message,
            'type' => $type
        ]);
    }

    // Reusable back with alert
    protected function alertBack($type = 'success', $title = 'Berhasil!', $message = 'Operasi berhasil dilakukan.')
    {
        return redirect()->back()->with('alert', [
            'title' => $title,
            'message' => $message,
            'type' => $type
        ]);
    }

    // Shortcut success redirect
    protected function success($route, $message = 'Berhasil!')
    {
        return $this->alertRedirect($route, 'success', 'Berhasil', $message);
    }

    // Shortcut success back
    protected function successBack($message = 'Berhasil!')
    {
        return $this->alertBack('success', 'Berhasil', $message);
    }

    // Shortcut error redirect
    protected function error($route, $message = 'Terjadi kesalahan.')
    {
        return $this->alertRedirect($route, 'error', 'Gagal', $message);
    }

    // Shortcut error back
    protected function errorBack($message = 'Terjadi kesalahan.')
    {
        return $this->alertBack('error', 'Gagal', $message);
    }

    // Shortcut warning redirect
    protected function warning($route, $message = 'Ada yang perlu diperhatikan.')
    {
        return $this->alertRedirect($route, 'warning', 'Perhatian', $message);
    }

    // Shortcut warning back
    protected function warningBack($message = 'Ada yang perlu diperhatikan.')
    {
        return $this->alertBack('warning', 'Perhatian', $message);
    }
}
