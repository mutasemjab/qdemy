<?php


namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Traits\HasNotifications;
use App\Traits\Responses;

class NotificationApiController extends Controller
{
    use HasNotifications, Responses;

    public function index()
    {
        $notifications = $this->getUserNotifications(true);

        return $this->success_response('Notifications retrieved successfully', $notifications);
    }

    public function read($id)
    {
        $notification = $this->markNotificationAsRead($id, true);

        return $this->success_response('Notification marked as read', $notification);
    }
}
