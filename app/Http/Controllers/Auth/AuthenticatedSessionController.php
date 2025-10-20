<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Visitor; // ✅ import model Visitor

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();
    //     $request->session()->regenerate();

    //     $user = Auth::user();

    //     // 🔹 Update last_activity saat login
    //     $user->update([
    //         'last_activity' => now(),
    //     ]);

    //     // ✅ Catat visit saat login berhasil
    //     Visitor::create([
    //         'user_id'    => $user->id,
    //         'ip_address' => $request->ip(),
    //         'user_agent' => $request->userAgent(),
    //     ]);

    //     // ✅ Tambahkan ini agar Sanctum token juga dibuat saat login biasa
    //     $token = $user->createToken('web-login')->plainTextToken;
    //     session(['sanctum_token' => $token]);

    //     // 🔹 Arahkan user sesuai perannya
    //     switch ($user->role) {
    //         case 'developer':
    //             return redirect()->intended('/dev/dashboard');
    //         case 'admin':
    //             return redirect()->intended('/admin/dashboard');
    //         case 'guru':
    //             return redirect()->intended('/guru/dashboard');
    //         case 'staff':
    //             return redirect()->intended('/staff/dashboard');
    //         case 'siswa':
    //             return redirect()->intended('/siswa/dashboard');
    //         case 'user':
    //         default:
    //             return redirect()->intended('/user/dashboard');
    //     }
    // }

    public function store(LoginRequest $request): RedirectResponse
    {
        $remember = $request->filled('remember'); // ✅ ambil checkbox

        // Auth attempt dengan remember
        $request->authenticate($remember); // ✨ Breeze LoginRequest bisa menerima parameter

        $request->session()->regenerate();

        $user = Auth::user();

        $user->update(['last_activity' => now()]);

        Visitor::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Buat Sanctum token juga (optional)
        $token = $user->createToken('web-login')->plainTextToken;
        session(['sanctum_token' => $token]);

        // Redirect sesuai role
        switch ($user->role) {
            case 'developer': return redirect()->intended('/dev/dashboard');
            case 'admin': return redirect()->intended('/admin/dashboard');
            case 'guru': return redirect()->intended('/guru/dashboard');
            case 'staff': return redirect()->intended('/staff/dashboard');
            case 'siswa': return redirect()->intended('/siswa/dashboard');
            case 'user':
            default: return redirect()->intended('/user/dashboard');
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function loginApi(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logoutApi(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
