<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\FCMController as AdminFCMController;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Http\Controllers\FCMController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:notification-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:notification-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:notification-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:notification-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $query = Notification::with(['user', 'teacher'])
            ->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(20);
        $users = User::where('role_name', 'student')->get();
        $teachers = User::where('role_name', 'teacher')->get();

        return view('admin.notifications.index', compact('notifications', 'users', 'teachers'));
    }

    /**
     * Show the form for creating a new notification.
     */
    public function create()
    {
        $users = User::where('role_name', 'student')->get();
        $teachers = User::where('role_name', 'teacher')->get();
        
        return view('admin.notifications.create', compact('users', 'teachers'));
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_type' => 'required|in:all_users,all_teachers,all,specific_user,specific_teacher',
            'user_id' => 'nullable|exists:users,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $recipients = $this->getRecipients($request);
            $sentCount = 0;
            $failedCount = 0;

            foreach ($recipients as $recipient) {
                // Create notification record
                $notification = Notification::create([
                    'user_id' => $recipient['user_id'],
                    'teacher_id' => $recipient['teacher_id'],
                    'title' => $request->title,
                    'body' => $request->body,
                ]);

                // Send FCM notification
                $user = User::find($recipient['user_id'] ?? $recipient['teacher_id']);
                if ($user && $user->fcm_token) {
                    $success = AdminFCMController::sendMessage(
                        $request->title,
                        $request->body,
                        $user->fcm_token,
                        $user->id,
                        'notification'
                    );

                    if ($success) {
                        $sentCount++;
                    } else {
                        $failedCount++;
                    }
                } else {
                    $sentCount++; // Count as sent even without FCM token
                }
            }

            DB::commit();

            $message = __('messages.notifications_sent_successfully', [
                'sent' => $sentCount,
                'failed' => $failedCount
            ]);

            return redirect()->route('notifications.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', __('messages.notification_send_failed'))->withInput();
        }
    }

    /**
     * Display the specified notification.
     */
    public function show(Notification $notification)
    {
        $notification->load(['user', 'teacher']);
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the notification.
     */
    public function edit(Notification $notification)
    {
        $users = User::where('role_name', 'student')->get();
        $teachers = User::where('role_name', 'teacher')->get();
        
        return view('admin.notifications.edit', compact('notification', 'users', 'teachers'));
    }

    /**
     * Update the specified notification.
     */
    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $notification->update([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user_id,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('notifications.index')
            ->with('success', __('messages.notification_updated_successfully'));
    }

    /**
     * Remove the specified notification.
     */
    public function destroy(Notification $notification)
    {
        try {
            $notification->delete();
            return redirect()->route('notifications.index')
                ->with('success', __('messages.notification_deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('messages.notification_deletion_failed'));
        }
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $notification->update(['read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => __('messages.notification_marked_as_read')
        ]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(Notification $notification)
    {
        $notification->update(['read_at' => null]);
        
        return response()->json([
            'success' => true,
            'message' => __('messages.notification_marked_as_unread')
        ]);
    }

    /**
     * Bulk delete notifications.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'notifications' => 'required|array',
            'notifications.*' => 'exists:notifications,id'
        ]);

        try {
            Notification::whereIn('id', $request->notifications)->delete();
            
            return response()->json([
                'success' => true,
                'message' => __('messages.notifications_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.bulk_delete_failed')
            ]);
        }
    }

    /**
     * Send test notification.
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'fcm_token' => 'required|string',
        ]);

        $success = FCMController::sendMessage(
            $request->title,
            $request->body,
            $request->fcm_token,
            Auth::id(),
            'test'
        );

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => __('messages.test_notification_sent')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('messages.test_notification_failed')
            ]);
        }
    }

    /**
     * Get statistics for notifications.
     */
    public function getStats()
    {
        $stats = [
            'total' => Notification::count(),
            'read' => Notification::whereNotNull('read_at')->count(),
            'unread' => Notification::whereNull('read_at')->count(),
            'today' => Notification::whereDate('created_at', today())->count(),
            'this_week' => Notification::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Notification::whereMonth('created_at', now()->month)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get recipients based on request type.
     */
    private function getRecipients(Request $request)
    {
        $recipients = [];

        switch ($request->recipient_type) {
            case 'all':
                $users = User::whereIn('role_name', ['student', 'teacher'])->get();
                foreach ($users as $user) {
                    $recipients[] = [
                        'user_id' => $user->role_name === 'student' ? $user->id : null,
                        'teacher_id' => $user->role_name === 'teacher' ? $user->id : null,
                    ];
                }
                break;

            case 'all_users':
                $users = User::where('role_name', 'student')->get();
                foreach ($users as $user) {
                    $recipients[] = [
                        'user_id' => $user->id,
                        'teacher_id' => Auth::id(),
                    ];
                }
                break;

            case 'all_teachers':
                $teachers = User::where('role_name', 'teacher')->get();
                foreach ($teachers as $teacher) {
                    $recipients[] = [
                        'user_id' => null,
                        'teacher_id' => $teacher->id,
                    ];
                }
                break;

            case 'specific_user':
                $recipients[] = [
                    'user_id' => $request->user_id,
                    'teacher_id' => Auth::id(),
                ];
                break;

            case 'specific_teacher':
                $recipients[] = [
                    'user_id' => null,
                    'teacher_id' => $request->teacher_id,
                ];
                break;
        }

        return $recipients;
    }

    /**
     * Resend notification.
     */
    public function resend(Notification $notification)
    {
        try {
            $user = $notification->user ?? $notification->teacher;
            
            if ($user && $user->fcm_token) {
                $success = FCMController::sendMessage(
                    $notification->title,
                    $notification->body,
                    $user->fcm_token,
                    $user->id,
                    'notification'
                );

                if ($success) {
                    return response()->json([
                        'success' => true,
                        'message' => __('messages.notification_resent_successfully')
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => __('messages.notification_resend_failed')
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.no_fcm_token_found')
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.notification_resend_failed')
            ]);
        }
    }
}