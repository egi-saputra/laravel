<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceToken;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    // Kirim notifikasi ke semua siswa / user tertentu
    public function sendToUsers(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'user_ids' => 'nullable|array', // optional
        ]);

        $tokensQuery = DeviceToken::query();

        if ($request->filled('user_ids')) {
            $tokensQuery->whereIn('user_id', $request->user_ids);
        }

        $tokens = $tokensQuery->pluck('device_token')->toArray();

        foreach ($tokens as $token) {
            $this->firebase->sendToToken(
                $token,
                $request->title,
                $request->body,
                $request->data ?? []
            );
        }

        return response()->json(['message' => 'Notifikasi terkirim']);
    }
}
