<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * List notifikasi untuk user yang login (JSON, untuk polling via Alpine.js).
     */
    public function index(Request $request): JsonResponse
    {
        $notifikasis = Notifikasi::where('iduser', $request->user()->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $unreadCount = Notifikasi::where('iduser', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifikasis' => $notifikasis,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(Request $request, Notifikasi $notifikasi): JsonResponse
    {
        if ($notifikasi->iduser !== $request->user()->id) {
            abort(403);
        }

        $notifikasi->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        Notifikasi::where('iduser', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
