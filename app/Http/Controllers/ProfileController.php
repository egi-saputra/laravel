<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function editPassword()
    {
        return view('profile.change-password');
    }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required','string','email','max:255', Rule::unique('users')->ignore($request->user()->id)],
    //     ]);

    //     $request->user()->update($request->only('name', 'email'));

    //     $role = $request->user()->role;
    //     $dashboardRoute = match($role) {
    //         'admin' => 'admin.dashboard',
    //         'guru'  => 'guru.dashboard',
    //         'staff' => 'staff.dashboard',
    //         'siswa' => 'siswa.dashboard',
    //         default => 'user.dashboard',
    //     };

    //     return redirect()->route($dashboardRoute)->with('alert', [
    //         'type' => 'success',
    //         'title' => 'Profile Updated!',
    //         'message' => 'Profil berhasil diperbarui!'
    //     ]);
    // }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string','email','max:255', Rule::unique('users')->ignore($request->user()->id)],
        ]);

        $user = $request->user();
        $emailChanged = $user->email !== $request->email;

        // Update name & email
        $user->update($request->only('name', 'email'));

        // Jika email berubah, kirim email verifikasi baru
        if ($emailChanged) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('profile.edit')->with('alert', [
                'type' => 'success',
                'title' => 'Profile Updated!',
                'message' => 'Profil berhasil diperbarui. Silakan verifikasi email baru kamu.'
            ]);
        }

        $role = $user->role;
        $dashboardRoute = match($role) {
            'admin' => 'admin.dashboard',
            'guru'  => 'guru.dashboard',
            'staff' => 'staff.dashboard',
            'siswa' => 'siswa.dashboard',
            default => 'user.dashboard',
        };

        return redirect()->route($dashboardRoute)->with('alert', [
            'type' => 'success',
            'title' => 'Profile Updated!',
            'message' => 'Profil berhasil diperbarui!'
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('alert', [
            'type' => 'success',
            'title' => 'Akun Dihapus',
            'message' => 'Akun kamu berhasil dihapus.'
        ]);
    }

}
