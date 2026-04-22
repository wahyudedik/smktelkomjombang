<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get user notifications
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // Get statistics
        $stats = [
            'total' => DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('notifiable_type', 'App\Models\User')
                ->count(),
            'unread' => DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('notifiable_type', 'App\Models\User')
                ->whereNull('read_at')
                ->count(),
        ];

        $stats['read'] = $stats['total'] - $stats['unread'];

        return view('notifications.index', compact('notifications', 'stats'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();

        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        $updated = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', "Marked {$updated} notifications as read");
    }

    public function delete($id)
    {
        $user = Auth::user();

        $deleted = DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', 'App\Models\User')
            ->delete();

        if (!$deleted) {
            return redirect()->back()->with('error', 'Notification not found');
        }

        return redirect()->back()->with('success', 'Notification deleted');
    }
}
