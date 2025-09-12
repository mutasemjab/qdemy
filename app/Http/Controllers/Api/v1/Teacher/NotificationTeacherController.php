<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Traits\Responses;
use App\Traits\HasNotifications;
use Illuminate\Http\Request;

class NotificationTeacherController extends Controller
{
    use Responses, HasNotifications;

    /**
     * Get teacher's notifications
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // Get notifications using the trait method
            $notifications = $this->getUserNotifications(true); // true = as array

            // If trait returns empty array, get notifications directly
            if (empty($notifications)) {
                // Based on your schema, assuming teacher notifications use teacher_id field
                $notificationQuery = Notification::where('teacher_id', $user->id);

                // Apply filters
                if ($request->filled('status')) {
                    if ($request->status === 'read') {
                        $notificationQuery->whereNotNull('read_at');
                    } elseif ($request->status === 'unread') {
                        $notificationQuery->whereNull('read_at');
                    }
                }

                if ($request->filled('search')) {
                    $search = $request->search;
                    $notificationQuery->where(function($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                          ->orWhere('body', 'like', "%{$search}%");
                    });
                }

                $notifications = $notificationQuery->latest()->paginate(20);

                $notificationsData = $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'body' => $notification->body,
                        'is_read' => $notification->read_at !== null,
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at,
                        'updated_at' => $notification->updated_at
                    ];
                });

                return $this->success_response('Notifications retrieved successfully', [
                    'notifications' => $notificationsData,
                    'pagination' => [
                        'current_page' => $notifications->currentPage(),
                        'last_page' => $notifications->lastPage(),
                        'per_page' => $notifications->perPage(),
                        'total' => $notifications->total()
                    ],
                    'summary' => [
                        'total_notifications' => $notifications->total(),
                        'unread_count' => Notification::where('teacher_id', $user->id)->whereNull('read_at')->count()
                    ]
                ]);
            }

            // If trait method works, format the response
            $notificationsData = collect($notifications)->map(function ($notification) {
                return [
                    'id' => $notification['id'],
                    'title' => $notification['title'],
                    'body' => $notification['body'],
                    'is_read' => $notification['read_at'] !== null,
                    'read_at' => $notification['read_at'],
                    'created_at' => $notification['created_at'],
                    'updated_at' => $notification['updated_at']
                ];
            });

            return $this->success_response('Notifications retrieved successfully', [
                'notifications' => $notificationsData,
                'summary' => [
                    'total_notifications' => count($notifications),
                    'unread_count' => collect($notifications)->where('read_at', null)->count()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve notifications: ' . $e->getMessage(), null);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $notificationId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // Find notification and ensure it belongs to this teacher
            $notification = Notification::where('id', $notificationId)
                ->where('teacher_id', $user->id) // Based on your schema
                ->first();

            if (!$notification) {
                return $this->error_response('Notification not found or access denied.', null);
            }

            // Use trait method if available, otherwise update directly
            try {
                $updatedNotification = $this->markNotificationAsRead($notificationId, true);
                
                return $this->success_response('Notification marked as read successfully', [
                    'notification' => [
                        'id' => $updatedNotification['id'],
                        'title' => $updatedNotification['title'],
                        'body' => $updatedNotification['body'],
                        'is_read' => true,
                        'read_at' => $updatedNotification['read_at'],
                        'created_at' => $updatedNotification['created_at'],
                        'updated_at' => $updatedNotification['updated_at']
                    ]
                ]);

            } catch (\Exception $e) {
                // Fallback: Update directly
                if (is_null($notification->read_at)) {
                    $notification->update(['read_at' => now()]);
                }

                return $this->success_response('Notification marked as read successfully', [
                    'notification' => [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'body' => $notification->body,
                        'is_read' => true,
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at,
                        'updated_at' => $notification->updated_at
                    ]
                ]);
            }

        } catch (\Exception $e) {
            return $this->error_response('Failed to mark notification as read: ' . $e->getMessage(), null);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // Mark all unread notifications as read
            $updatedCount = Notification::where('teacher_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return $this->success_response('All notifications marked as read successfully', [
                'updated_count' => $updatedCount
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to mark all notifications as read: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $unreadCount = Notification::where('teacher_id', $user->id)
                ->whereNull('read_at')
                ->count();

            return $this->success_response('Unread count retrieved successfully', [
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to get unread count: ' . $e->getMessage(), null);
        }
    }




 
}