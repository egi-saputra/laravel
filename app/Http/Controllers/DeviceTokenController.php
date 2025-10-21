<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceToken;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'device_token' => 'required|string',
            'platform' => 'required|in:android,ios',
        ]);

        $user = $request->user(); // ambil user dari auth

        // Simpan atau update token FCM
        DeviceToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_token' => $request->device_token
            ],
            [
                'platform' => $request->platform
            ]
        );

        return response()->json(['message' => 'Token FCM tersimpan']);
    }
}
