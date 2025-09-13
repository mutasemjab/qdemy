<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


trait HasNotifications
{
    public function getUserNotifications($asJson = false)
    {
        $user = Auth::user();

        if ($user->role_name === 'student') {
            $notifications = Notification::where('user_id', $user->id)->latest()->get();
        } elseif ($user->role_name === 'teacher') {
            $notifications = Notification::where('teacher_id', $user->id)->latest()->get();
        } elseif ($user->role_name === 'parent') {
            $notifications = Notification::where('parent_id', $user->id)->latest()->get();
        } else {
            $notifications = collect();
        }

        return $asJson ? $notifications->toArray() : $notifications;
    }

    public function markNotificationAsRead($id, $asJson = false)
    {
        $notification = \App\Models\Notification::findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        if ($asJson) {
            return $notification->toArray();
        }

        // For web: redirect back with a success message
        return redirect()->back()->with('success', __('panel.notification_read'));
    }

    /**
     * Get unread notifications count for current user
     */
    public function getUnreadNotificationsCount()
    {
        $user = Auth::user();

        if ($user->role_name === 'student') {
            return Notification::where('user_id', $user->id)->whereNull('read_at')->count();
        } elseif ($user->role_name === 'teacher') {
            return Notification::where('teacher_id', $user->id)->whereNull('read_at')->count();
        } elseif ($user->role_name === 'parent') {
            return Notification::where('parent_id', $user->id)->whereNull('read_at')->count();
        }

        return 0;
    }

    /**
     * Mark all notifications as read for current user
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();

        if ($user->role_name === 'student') {
            return Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } elseif ($user->role_name === 'teacher') {
            return Notification::where('teacher_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } elseif ($user->role_name === 'parent') {
            return Notification::where('parent_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return 0;
    }
}
