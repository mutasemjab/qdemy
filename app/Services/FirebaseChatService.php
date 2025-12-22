<?php

namespace App\Services;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Parentt;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseChatService
{
    private $projectId;
    private $baseUrl;
    private $factory;
    
    public function __construct()
    {
        $this->projectId = config('firebase.project_id');
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
        
        try {
            // Try to initialize Firebase Factory for other services
            $this->factory = (new Factory)
                ->withProjectId($this->projectId);
                
            if (config('firebase.credentials')) {
                $this->factory = $this->factory->withServiceAccount(config('firebase.credentials'));
            }
        } catch (\Exception $e) {
            Log::warning('Firebase Factory initialization failed: ' . $e->getMessage());
            $this->factory = null;
        }
    }

    /**
     * Get or create user document in Firestore using REST API
     */
    public function getOrCreateFirebaseUser(User $user): string
    {
        $chatUid = $user->role_name . '|' . $user->id;
        $firebaseUid = 'user_' . $user->id;
        
        try {
            // Check if user exists
            $response = Http::timeout(10)->get("{$this->baseUrl}/users/{$firebaseUid}");
            
            if ($response->status() === 404) {
                // User doesn't exist, create it
                $userData = [
                    'fields' => [
                        'chatUid' => ['stringValue' => $chatUid],
                        'role' => ['stringValue' => $user->role_name],
                        'appUserId' => ['stringValue' => (string)$user->id],
                        'name' => ['stringValue' => $user->name],
                        'avatarUrl' => ['stringValue' => $user->photo_url ?? ''],
                        'fcmTokens' => [
                            'arrayValue' => [
                                'values' => $user->fcm_token ? [['stringValue' => $user->fcm_token]] : []
                            ]
                        ],
                        'createdAt' => ['timestampValue' => now()->toISOString()]
                    ]
                ];
                
                // Add specific data based on role
                if ($user->role_name === 'teacher' && $user->teacher) {
                    $courses = $user->teacher->courses->pluck('id')->map(fn($id) => ['stringValue' => 'c_' . $id])->toArray();
                    $userData['fields']['allowedCourses'] = [
                        'arrayValue' => ['values' => $courses]
                    ];
                }
                
                Http::timeout(10)->patch("{$this->baseUrl}/users/{$firebaseUid}", $userData);
            }
            
            return $firebaseUid;
        } catch (\Exception $e) {
            Log::error('Error creating Firebase user: ' . $e->getMessage());
            return $firebaseUid; // Return the UID even if creation failed
        }
    }

    /**
     * Create or get existing chat between participants
     */
    public function createOrGetChat(array $participantUserIds, ?string $courseId = null): string
    {
        // Create chat UIDs for participants
        $participants = [];
        $participantsMeta = [];
        
        foreach ($participantUserIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $chatUid = $user->role_name . '|' . $user->id;
                $participants[] = $chatUid;
                $participantsMeta[$chatUid] = [
                    'name' => $user->name,
                    'avatarUrl' => $user->photo_url ?? ''
                ];
            }
        }
        
        // Sort participants for consistent chat ID generation
        sort($participants);
        $chatId = 'chat_' . md5(implode('_', $participants) . ($courseId ?? ''));
        
        try {
            // Check if chat exists
            $response = Http::timeout(10)->get("{$this->baseUrl}/chats/{$chatId}");
            
            if ($response->status() === 404) {
                // Chat doesn't exist, create it
                $this->createChatDocument($chatId, $participants, $participantsMeta, $participantUserIds, $courseId);
            }
            
            return $chatId;
        } catch (\Exception $e) {
            Log::error('Error creating chat: ' . $e->getMessage());
            return $chatId;
        }
    }

    private function createChatDocument(string $chatId, array $participants, array $participantsMeta, array $participantUserIds, ?string $courseId): void
    {
        $participantsArray = array_map(fn($p) => ['stringValue' => $p], $participants);
        
        $metaFields = [];
        foreach ($participantsMeta as $uid => $meta) {
            $metaFields[$uid] = [
                'mapValue' => [
                    'fields' => [
                        'name' => ['stringValue' => $meta['name']],
                        'avatarUrl' => ['stringValue' => $meta['avatarUrl']]
                    ]
                ]
            ];
        }
        
        $unreadFields = [];
        foreach ($participants as $participant) {
            $unreadFields[$participant] = ['integerValue' => '0'];
        }
        
        $chatData = [
            'fields' => [
                'participants' => [
                    'arrayValue' => ['values' => $participantsArray]
                ],
                'participantsMeta' => [
                    'mapValue' => ['fields' => $metaFields]
                ],
                'lastMessage' => ['stringValue' => ''],
                'lastMessageAt' => ['timestampValue' => now()->toISOString()],
                'lastSender' => ['stringValue' => ''],
                'unread' => [
                    'mapValue' => ['fields' => $unreadFields]
                ],
                'createdAt' => ['timestampValue' => now()->toISOString()]
            ]
        ];
        
        if ($courseId) {
            $chatData['fields']['courseId'] = ['stringValue' => $courseId];
        }
        
        // Add teacherId if there's a teacher in participants
        foreach ($participantUserIds as $userId) {
            $user = User::find($userId);
            if ($user && $user->role_name === 'teacher') {
                $chatData['fields']['teacherId'] = ['stringValue' => (string)$user->id];
                break;
            }
        }
        
        Http::timeout(10)->patch("{$this->baseUrl}/chats/{$chatId}", $chatData);
    }

    /**
     * Send a message to a chat
     */
    public function sendMessage(string $chatId, string $senderUserId, string $message, string $type = 'text'): string
    {
        try {
            $sender = User::find($senderUserId);
            $senderChatUid = $sender->role_name . '|' . $sender->id;
            
            $messageId = 'msg_' . uniqid() . '_' . time();
            
            $messageData = [
                'fields' => [
                    'sender' => ['stringValue' => $senderChatUid],
                    'type' => ['stringValue' => $type],
                    'text' => ['stringValue' => $message],
                    'createdAt' => ['timestampValue' => now()->toISOString()],
                    'readBy' => [
                        'mapValue' => [
                            'fields' => [
                                $senderChatUid => ['timestampValue' => now()->toISOString()]
                            ]
                        ]
                    ],
                    'status' => ['stringValue' => 'sent']
                ]
            ];
            
            // Add message to subcollection
            Http::timeout(10)->patch("{$this->baseUrl}/chats/{$chatId}/messages/{$messageId}", $messageData);
            
            // Update chat's last message info
            $this->updateChatLastMessage($chatId, $message, $senderChatUid);
            
            return $messageId;
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            return 'error_' . time();
        }
    }

    /**
     * Update chat's last message information
     */
    private function updateChatLastMessage(string $chatId, string $message, string $senderChatUid): void
    {
        try {
            // For simplicity, we'll do a basic update without fetching current unread counts
            // In a production app, you'd want to fetch current data first
            $updateData = [
                'fields' => [
                    'lastMessage' => ['stringValue' => $message],
                    'lastMessageAt' => ['timestampValue' => now()->toISOString()],
                    'lastSender' => ['stringValue' => $senderChatUid]
                ]
            ];
            
            Http::timeout(10)->patch(
                "{$this->baseUrl}/chats/{$chatId}?updateMask.fieldPaths=lastMessage&updateMask.fieldPaths=lastMessageAt&updateMask.fieldPaths=lastSender", 
                $updateData
            );
        } catch (\Exception $e) {
            Log::error('Error updating chat last message: ' . $e->getMessage());
        }
    }

    /**
     * Get available teachers for a student to chat with
     */
    public function getAvailableTeachersForStudent(int $studentId): array
    {
        $student = User::find($studentId);
        if (!$student || $student->role_name !== 'student') {
            return [];
        }

        // Get teachers from student's enrolled courses
        $courseIds = $student->courses->pluck('id');
        $teachers = Teacher::whereHas('courses', function($query) use ($courseIds) {
            $query->whereIn('id', $courseIds);
        })->with('user')->get();

        return $teachers->map(function($teacher) {
            return [
                'id' => $teacher->user_id,
                'name' => $teacher->user->name,
                'subject' => $teacher->name_of_lesson,
                'avatar' => $teacher->photo_url ?? asset('assets_front/images/Profile-picture.jpg')
            ];
        })->toArray();
    }

    /**
     * Get students that a parent can chat about with teachers
     */
    public function getParentStudents(int $parentId): array
    {
        $parent = User::find($parentId);
        if (!$parent || $parent->role_name !== 'parent') {
            return [];
        }

        $parentRecord = $parent->parent;
        if (!$parentRecord) {
            return [];
        }

        return $parentRecord->students->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->clas->name ?? 'Unknown',
                'avatar' => $student->photo_url ?? asset('assets_front/images/Profile-picture.jpg')
            ];
        })->toArray();
    }

    /**
     * Get teachers for a specific student (for parent to contact)
     */
    public function getTeachersForStudent(int $studentId): array
    {
        $student = User::find($studentId);
        if (!$student || $student->role_name !== 'student') {
            return [];
        }

        $courseIds = $student->courses->pluck('id');
        $teachers = Teacher::whereHas('courses', function($query) use ($courseIds) {
            $query->whereIn('id', $courseIds);
        })->with('user')->get();

        return $teachers->map(function($teacher) use ($studentId) {
            return [
                'id' => $teacher->user_id,
                'name' => $teacher->user->name,
                'subject' => $teacher->name_of_lesson,
                'avatar' => $teacher->photo_url ?? asset('assets_front/images/Profile-picture.jpg'),
                'student_id' => $studentId
            ];
        })->toArray();
    }



    /**
     * Get user's chats using a simpler query that doesn't require complex indexing
     */
    public function getUserChats(int $userId): array
    {
        $user = User::find($userId);
        $chatUid = $user->role_name . '|' . $user->id;
        
        try {
            // Simple query that only filters by participants (no ordering to avoid index requirements)
            $response = Http::timeout(10)->post("{$this->baseUrl}:runQuery", [
                'structuredQuery' => [
                    'from' => [['collectionId' => 'chats']],
                    'where' => [
                        'fieldFilter' => [
                            'field' => ['fieldPath' => 'participants'],
                            'op' => 'ARRAY_CONTAINS',
                            'value' => ['stringValue' => $chatUid]
                        ]
                    ]
                    // Removed orderBy to avoid index requirement
                ]
            ]);
            
            $chats = [];
            if ($response->successful()) {
                $results = $response->json();
                
                foreach ($results as $result) {
                    if (isset($result['document'])) {
                        $doc = $result['document'];
                        $fields = $doc['fields'] ?? [];
                        
                        // Extract participants
                        $participants = [];
                        if (isset($fields['participants']['arrayValue']['values'])) {
                            foreach ($fields['participants']['arrayValue']['values'] as $p) {
                                $participants[] = $p['stringValue'];
                            }
                        }
                        
                        // Extract participants metadata
                        $participantsMeta = [];
                        if (isset($fields['participantsMeta']['mapValue']['fields'])) {
                            foreach ($fields['participantsMeta']['mapValue']['fields'] as $uid => $meta) {
                                $participantsMeta[$uid] = [
                                    'name' => $meta['mapValue']['fields']['name']['stringValue'] ?? '',
                                    'avatarUrl' => $meta['mapValue']['fields']['avatarUrl']['stringValue'] ?? ''
                                ];
                            }
                        }
                        
                        // Extract unread count for current user
                        $unreadCount = 0;
                        if (isset($fields['unread']['mapValue']['fields'][$chatUid]['integerValue'])) {
                            $unreadCount = (int)$fields['unread']['mapValue']['fields'][$chatUid]['integerValue'];
                        }
                        
                        // Get chat ID from document name
                        $chatId = basename($doc['name']);
                        
                        // Parse lastMessageAt timestamp
                        $lastMessageAt = '';
                        if (isset($fields['lastMessageAt']['timestampValue'])) {
                            $lastMessageAt = $fields['lastMessageAt']['timestampValue'];
                        }
                        
                        $chats[] = [
                            'id' => $chatId,
                            'participants' => $participants,
                            'participantsMeta' => $participantsMeta,
                            'lastMessage' => $fields['lastMessage']['stringValue'] ?? '',
                            'lastMessageAt' => $lastMessageAt,
                            'lastSender' => $fields['lastSender']['stringValue'] ?? '',
                            'unreadCount' => $unreadCount
                        ];
                    }
                }
                
                // Sort chats by lastMessageAt in PHP instead of Firestore
                usort($chats, function($a, $b) {
                    $timeA = $a['lastMessageAt'] ? strtotime($a['lastMessageAt']) : 0;
                    $timeB = $b['lastMessageAt'] ? strtotime($b['lastMessageAt']) : 0;
                    return $timeB - $timeA; // Descending order (newest first)
                });
                
            } else {
                Log::warning('Failed to fetch user chats', [
                    'user_id' => $userId,
                    'response_status' => $response->status(),
                    'response_body' => $response->body()
                ]);
            }
            
            Log::info('Successfully fetched user chats', [
                'user_id' => $userId,
                'chat_count' => count($chats)
            ]);
            
            return $chats;
            
        } catch (\Exception $e) {
            Log::error('Error fetching user chats: ' . $e->getMessage(), [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Get chat messages - simplified version
     */
  
public function getChatMessages(string $chatId, int $limit = 50): array
{
    try {
        // Direct access to subcollection using list documents
        $messagesUrl = "{$this->baseUrl}/chats/{$chatId}/messages";
        $response = Http::timeout(10)->get($messagesUrl, [
            'pageSize' => $limit,
            'orderBy' => 'createdAt'
        ]);
        
        $messages = [];
        if ($response->successful()) {
            $result = $response->json();
            
            if (isset($result['documents'])) {
                foreach ($result['documents'] as $doc) {
                    $fields = $doc['fields'] ?? [];
                    
                    // Extract message data
                    $message = [
                        'id' => basename($doc['name']),
                        'sender' => $fields['sender']['stringValue'] ?? '',
                        'type' => $fields['type']['stringValue'] ?? 'text',
                        'text' => $fields['text']['stringValue'] ?? '',
                        'status' => $fields['status']['stringValue'] ?? 'sent'
                    ];
                    
                    // Parse timestamp
                    if (isset($fields['createdAt']['timestampValue'])) {
                        $timestamp = $fields['createdAt']['timestampValue'];
                        $message['createdAt'] = [
                            'seconds' => strtotime($timestamp)
                        ];
                    }
                    
                    // Parse readBy
                    $readBy = [];
                    if (isset($fields['readBy']['mapValue']['fields'])) {
                        foreach ($fields['readBy']['mapValue']['fields'] as $uid => $timestamp) {
                            $readBy[$uid] = $timestamp['timestampValue'] ?? '';
                        }
                    }
                    $message['readBy'] = $readBy;
                    
                    $messages[] = $message;
                }
                
                // Sort messages by timestamp (oldest first) - backup sorting in case orderBy doesn't work
                usort($messages, function($a, $b) {
                    $timeA = $a['createdAt']['seconds'] ?? 0;
                    $timeB = $b['createdAt']['seconds'] ?? 0;
                    return $timeA - $timeB;
                });
            }
        } else {
            Log::warning('Failed to fetch chat messages', [
                'chat_id' => $chatId,
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);
        }
        
        Log::info('Successfully fetched chat messages', [
            'chat_id' => $chatId,
            'message_count' => count($messages),
            'sample_message' => !empty($messages) ? $messages[0] : null
        ]);
        
        return $messages;
        
    } catch (\Exception $e) {
        Log::error('Error fetching chat messages: ' . $e->getMessage(), [
            'chat_id' => $chatId,
            'error' => $e->getMessage()
        ]);
        return [];
    }
}

    /**
     * Mark chat as read for a user - simplified version
     */
    public function markChatAsRead(string $chatId, int $userId): void
    {
        // For now, do nothing to avoid errors
        // You can implement this later
    }

    /**
     * Check if user can chat with another user
     */
    public function canUsersChatTogether(int $user1Id, int $user2Id, ?int $studentId = null): bool
    {
        $user1 = User::find($user1Id);
        $user2 = User::find($user2Id);
        
        if (!$user1 || !$user2) {
            return false;
        }
        
        // Student can chat with their teachers
        if ($user1->role_name === 'student' && $user2->role_name === 'teacher') {
            return $this->isStudentEnrolledWithTeacher($user1Id, $user2Id);
        }
        
        // Teacher can chat with their students
        if ($user1->role_name === 'teacher' && $user2->role_name === 'student') {
            return $this->isStudentEnrolledWithTeacher($user2Id, $user1Id);
        }
        
        // Parent can chat with their children's teachers
        if ($user1->role_name === 'parent' && $user2->role_name === 'teacher' && $studentId) {
            return $this->isParentStudentRelated($user1Id, $studentId) && 
                   $this->isStudentEnrolledWithTeacher($studentId, $user2Id);
        }
        
        // Teacher can chat with parents of their students
        if ($user1->role_name === 'teacher' && $user2->role_name === 'parent' && $studentId) {
            return $this->isParentStudentRelated($user2Id, $studentId) && 
                   $this->isStudentEnrolledWithTeacher($studentId, $user1Id);
        }
        
        return false;
    }
    
    private function isStudentEnrolledWithTeacher(int $studentId, int $teacherId): bool
    {
        $student = User::find($studentId);
        $teacher = Teacher::where('user_id', $teacherId)->first();
        
        if (!$student || !$teacher) {
            return false;
        }
        
        $studentCourseIds = $student->courses->pluck('id');
        $teacherCourseIds = $teacher->courses->pluck('id');
        
        return $studentCourseIds->intersect($teacherCourseIds)->isNotEmpty();
    }
    
    private function isParentStudentRelated(int $parentId, int $studentId): bool
    {
        $parent = User::find($parentId);
        if (!$parent || $parent->role_name !== 'parent') {
            return false;
        }
        
        $parentRecord = $parent->parent;
        if (!$parentRecord) {
            return false;
        }
        
        return $parentRecord->students()->where('user_id', $studentId)->exists();
    }
}