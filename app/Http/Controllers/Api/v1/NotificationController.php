<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Admin\FCMController as AdminFCMController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FCMController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    /**
     * Send notification to a specific user
     */
    public function sendToUser(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'sender_id' => 'required|integer|exists:users,id',
                'receiver_id' => 'required|integer|exists:users,id|different:sender_id',
                'title' => 'required|string|max:255',
                'body' => 'required|string|max:1000',
                'screen' => 'nullable|string|max:50'
            ]);

            // Get sender
            $sender = User::find($validated['sender_id']);
            if (!$sender) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sender not found'
                ], 404);
            }

            // Get receiver
            $receiver = User::find($validated['receiver_id']);
            if (!$receiver) {
                return response()->json([
                    'status' => false,
                    'message' => 'Receiver not found'
                ], 404);
            }

            if (!$receiver->fcm_token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Receiver does not have FCM token registered'
                ], 400);
            }

        

            // Send the notification
            $result = AdminFCMController::sendMessage(
                $validated['title'],
                $validated['body'],
                $receiver->fcm_token,
                $receiver->id,
                $validated['screen'] ?? 'default'
            );

            if ($result) {

                return response()->json([
                    'status' => true,
                    'message' => 'Notification sent successfully',
                    'data' => [
                        'sender_id' => $sender->id,
                        'sender_name' => $sender->name,
                        'receiver_id' => $receiver->id,
                        'receiver_name' => $receiver->name,
                        'title' => $validated['title'],
                        'body' => $validated['body']
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to send notification'
                ], 500);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Notification API Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

  
}

