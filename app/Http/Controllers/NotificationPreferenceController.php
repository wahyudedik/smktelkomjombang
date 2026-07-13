<?php

namespace App\Http\Controllers;

use App\Models\NotificationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationPreferenceController extends Controller
{
    /**
     * Display notification preferences form.
     */
    public function index()
    {
        $user = Auth::user();
        $preferences = $user->notification_preferences ?? $this->getDefaultPreferences();

        return view('notifications.preferences', compact('preferences'));
    }

    /**
     * Update notification preferences.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'preferences' => 'required|array',
            'preferences.all' => 'boolean',
            'preferences.email' => 'boolean',
            'preferences.push' => 'boolean',
            'preferences.general' => 'boolean',
            'preferences.security' => 'boolean',
            'preferences.graduation' => 'boolean',
            'preferences.voting' => 'boolean',
            'preferences.sarpras' => 'boolean',
            'preferences.announcement' => 'boolean',
            'preferences.reminder' => 'boolean',
            'preferences.approval' => 'boolean',
            'preferences.data_change' => 'boolean',
        ]);

        $user = Auth::user();
        $user->update([
            'notification_preferences' => $validated['preferences'],
        ]);

        return redirect()->route('admin.notifications.preferences')
            ->with('success', 'Pengaturan notifikasi berhasil disimpan');
    }

    /**
     * Reset preferences to defaults.
     */
    public function reset()
    {
        $user = Auth::user();
        $user->update([
            'notification_preferences' => $this->getDefaultPreferences(),
        ]);

        return redirect()->route('admin.notifications.preferences')
            ->with('success', 'Pengaturan notifikasi berhasil direset ke default');
    }

    /**
     * Display notification history.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $query = NotificationHistory::where('user_id', $user->id);

        // Filter by channel
        if ($request->filled('channel')) {
            $query->channel($request->channel);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->type($request->type);
        }

        // Search by title or message
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $history = $query->latest()->paginate(20);

        // Get statistics
        $stats = [
            'total' => NotificationHistory::where('user_id', $user->id)->count(),
            'sent' => NotificationHistory::where('user_id', $user->id)->status('sent')->count(),
            'failed' => NotificationHistory::where('user_id', $user->id)->status('failed')->count(),
            'today' => NotificationHistory::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
        ];

        return view('notifications.history', compact('history', 'stats'));
    }

    /**
     * Get default notification preferences.
     */
    private function getDefaultPreferences(): array
    {
        return [
            'all' => true,
            'email' => true,
            'push' => true,
            'general' => true,
            'security' => true,
            'graduation' => true,
            'voting' => true,
            'sarpras' => true,
            'announcement' => true,
            'reminder' => true,
            'approval' => true,
            'data_change' => true,
        ];
    }
}
