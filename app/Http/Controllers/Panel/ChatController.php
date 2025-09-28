<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FirebaseChatService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private $chatService;
    
    public function __construct(FirebaseChatService $chatService)
    {
        $this->chatService = $chatService;
        $this->middleware('auth');
    }

    /**
     * Show the chat interface
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's chats
        $chats = $this->chatService->getUserChats($user->id);
        
        // Get available contacts based on user role
        $availableContacts = $this->getAvailableContacts($user);
        
        return view('panel.chat.index', compact('chats', 'availableContacts', 'user'));
    }

    /**
     * Get available contacts for the current user
     */
    private function getAvailableContacts(User $user): array
    {
        switch ($user->role_name) {
            case 'student':
                return [
                    'teachers' => $this->chatService->getAvailableTeachersForStudent($user->id)
                ];
                
            case 'parent':
                $students = $this->chatService->getParentStudents($user->id);
                $availableTeachers = [];
                
                foreach ($students as $student) {
                    $teachers = $this->chatService->getTeachersForStudent($student['id']);
                    foreach ($teachers as $teacher) {
                        $teacher['student_context'] = $student;
                        $availableTeachers[] = $teacher;
                    }
                }
                
                return [
                    'students' => $students,
                    'teachers' => $availableTeachers
                ];
                
            case 'teacher':
                // For teachers, we'll load contacts dynamically when they receive messages
                return [];
                
            default:
                return [];
        }
    }

    /**
     * Start a new chat
     */

    public function startChat(Request $request)
    {
        // Log the incoming request
        \Log::info('StartChat request received', [
            'request_data' => $request->all(),
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role_name,
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept')
        ]);
        
        try {
            $request->validate([
                'participant_id' => 'required|integer|exists:users,id',
                'student_id' => 'nullable|integer|exists:users,id'
            ]);
            
            $currentUser = Auth::user();
            $participantId = $request->participant_id;
            $studentId = $request->student_id;
            
            \Log::info('Validation passed', [
                'current_user' => $currentUser->id,
                'participant_id' => $participantId,
                'student_id' => $studentId
            ]);
            
            // Check if users can chat together
            $canChat = $this->chatService->canUsersChatTogether($currentUser->id, $participantId, $studentId);
            
            if (!$canChat) {
                \Log::warning('Chat not allowed');
                return response()->json([
                    'success' => false,
                    'error' => 'You are not allowed to chat with this user'
                ], 403);
            }
            
            // Create or get existing chat
            $participantIds = [$currentUser->id, $participantId];
            $chatId = $this->chatService->createOrGetChat($participantIds);
            
            \Log::info('Chat created/retrieved', ['chat_id' => $chatId]);
            
            // Ensure Firebase users exist
            $this->chatService->getOrCreateFirebaseUser($currentUser);
            $this->chatService->getOrCreateFirebaseUser(User::find($participantId));
            
            \Log::info('Firebase users created successfully');
            
            return response()->json([
                'success' => true,
                'chatId' => $chatId,
                'message' => 'Chat created successfully',
                'redirect' => route('chat.index')
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error in startChat', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to create chat: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Show specific chat
     */
    public function show(string $chatId)
    {
        $user = Auth::user();
        $messages = $this->chatService->getChatMessages($chatId);
        
        // Mark chat as read
        $this->chatService->markChatAsRead($chatId, $user->id);
        
        return view('panel.chat.show', compact('chatId', 'messages', 'user'));
    }

    /**
     * Send a message
     */

public function sendMessage(Request $request)
{
    \Log::info('SendMessage request received', [
        'request_data' => $request->all(),
        'user_id' => Auth::id()
    ]);
    
    try {
        $request->validate([
            'chat_id' => 'required|string',
            'message' => 'required|string|max:1000',
            'type' => 'in:text,image,file'
        ]);
        
        $user = Auth::user();
        $chatId = $request->chat_id;
        $message = $request->message;
        $type = $request->type ?? 'text';
        
        \Log::info('Sending message', [
            'chat_id' => $chatId,
            'user_id' => $user->id,
            'message_length' => strlen($message)
        ]);
        
        // Send message using Firebase service
        $messageId = $this->chatService->sendMessage($chatId, $user->id, $message, $type);
        
        \Log::info('Message sent successfully', [
            'message_id' => $messageId,
            'chat_id' => $chatId
        ]);
        
        return response()->json([
            'success' => true,
            'messageId' => $messageId,
            'sender' => $user->role_name . '|' . $user->id,
            'timestamp' => now()->toISOString(),
            'message' => 'Message sent successfully'
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Message validation failed', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'error' => 'Validation failed: ' . implode(', ', array_flatten($e->errors()))
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Error sending message', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'error' => 'Failed to send message: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Get chat messages (AJAX)
     */
    public function getMessages(string $chatId)
    {
        $messages = $this->chatService->getChatMessages($chatId);
        return response()->json($messages);
    }

    /**
     * Get user's chats (AJAX)
     */
    public function getChats()
    {
        $user = Auth::user();
        $chats = $this->chatService->getUserChats($user->id);
        return response()->json($chats);
    }

    /**
     * Search for users to chat with
     */
    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'type' => 'in:teacher,student,parent'
        ]);
        
        $user = Auth::user();
        $query = $request->query;
        $type = $request->type;
        
        $results = [];
        
        switch ($user->role_name) {
            case 'student':
                if ($type === 'teacher') {
                    $teachers = $this->chatService->getAvailableTeachersForStudent($user->id);
                    $results = array_filter($teachers, function($teacher) use ($query) {
                        return stripos($teacher['name'], $query) !== false ||
                               stripos($teacher['subject'], $query) !== false;
                    });
                }
                break;
                
            case 'parent':
                if ($type === 'teacher') {
                    $students = $this->chatService->getParentStudents($user->id);
                    foreach ($students as $student) {
                        $teachers = $this->chatService->getTeachersForStudent($student['id']);
                        foreach ($teachers as $teacher) {
                            if (stripos($teacher['name'], $query) !== false ||
                                stripos($teacher['subject'], $query) !== false) {
                                $teacher['student_context'] = $student;
                                $results[] = $teacher;
                            }
                        }
                    }
                }
                break;
        }
        
        return response()->json($results);
    }

    /**
     * Mark chat as read
     */
    public function markAsRead(string $chatId)
    {
        $user = Auth::user();
        $this->chatService->markChatAsRead($chatId, $user->id);
        
        return response()->json(['success' => true]);
    }
}