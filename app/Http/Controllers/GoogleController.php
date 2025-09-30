<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Kalau user belum ada → buat baru dengan role default "user"
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => null, // dummy password
                    'role' => 'user',
                ]);
            }

            // Login user
            Auth::login($user);

            // Redirect sesuai role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'siswa':
                    return redirect()->route('siswa.dashboard');
                case 'guru':
                    return redirect()->route('guru.dashboard');
                case 'staff':
                    return redirect()->route('staff.dashboard');
                default:
                    return redirect()->route('user.dashboard');
            }

        } catch (\Exception $e) {
            return redirect('/login')->with('status', 'Gagal login dengan Google.');
        }
    }
}
