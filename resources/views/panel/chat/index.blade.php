@extends('layouts.app')

@section('title', __('panel.messages'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="chat-wrapper">
                <div class="chat-header-main">
                    <h4 class="chat-title">{{ __('panel.messages') }}</h4>
                </div>
                
                <div class="chat-main-container">
                    <!-- Left Side - Chat Area -->
                    <div class="chat-area">
                        <!-- Existing Chats List -->
                        @if(!empty($chats))
                            <div class="existing-chats">
                                <h6>Recent Conversations</h6>
                                <div class="chat-threads">
                                    @foreach($chats as $chat)
                                        @php
                                            $otherParticipant = null;
                                            foreach($chat['participants'] as $participant) {
                                                if($participant !== ($user->role_name . '|' . $user->id)) {
                                                    $otherParticipant = $chat['participantsMeta'][$participant] ?? null;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        
                                        @if($otherParticipant)
                                        <div class="chat-thread {{ $loop->first ? 'active' : '' }}" data-chat-id="{{ $chat['id'] }}">
                                            <div class="thread-avatar-container">
                                                <img src="{{ $otherParticipant['avatarUrl'] ?? asset('assets_front/images/Profile-picture.png') }}" 
                                                     alt="{{ $otherParticipant['name'] }}" class="thread-avatar">
                                                @if($chat['unreadCount'] > 0)
                                                    <span class="unread-indicator">{{ $chat['unreadCount'] }}</span>
                                                @endif
                                            </div>
                                            <div class="thread-content">
                                                <div class="thread-name">{{ $otherParticipant['name'] }}</div>
                                                @if($chat['lastMessage'])
                                                    <div class="thread-last-message">{{ Str::limit($chat['lastMessage'], 35) }}</div>
                                                @else
                                                    <div class="thread-last-message text-muted">No messages yet</div>
                                                @endif
                                            </div>
                                            <div class="thread-time">
                                                @if($chat['lastMessageAt'])
                                                    <small>{{ \Carbon\Carbon::parse($chat['lastMessageAt'])->diffForHumans() }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Active Chat Area -->
                        <div class="active-chat">
                            <!-- Chat Header -->
                            <div class="active-chat-header" id="chatHeader" style="display: none;">
                                <div class="chat-participant-info">
                                    <img id="chatAvatar" src="" alt="" class="participant-avatar">
                                    <div class="participant-details">
                                        <h6 id="chatParticipantName" class="participant-name"></h6>
                                        <small id="chatStatus" class="participant-status">Online</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Chat Messages -->
                            <div class="chat-messages" id="udChat">
                                <div class="welcome-message">
                                    <div class="welcome-icon">
                                        <i class="fa-solid fa-comment-dots"></i>
                                    </div>
                                    <h5>Welcome to Messages</h5>
                                    <p>Select a conversation or start a new chat with a teacher</p>
                                </div>
                            </div>
                            
                            <!-- Message Input -->
                            <div class="message-input-area" id="chatBox" style="display: none;">
                                <form id="messageForm" class="message-form">
                                    <input type="hidden" id="currentChatId" value="">
                                    <div class="input-container">
                                        <input type="text" id="messageInput" placeholder="Type your message..." class="message-input" required>
                                        <button type="submit" class="send-button">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Available Teachers -->
                    <div class="teachers-panel">
                        @if(in_array($user->role_name, ['student', 'parent']))
                            <div class="teachers-header">
                                @if($user->role_name === 'student')
                                    <h6>Available Teachers</h6>
                                @else
                                    <h6>Your Children's Teachers</h6>
                                @endif
                            </div>
                            
                            <div class="teachers-list">
                                @forelse($availableContacts['teachers'] ?? [] as $teacher)
                                    <div class="teacher-item" data-teacher-id="{{ $teacher['id'] }}" 
                                         @if($user->role_name === 'parent') data-student-id="{{ $teacher['student_id'] }}" @endif>
                                        <div class="teacher-avatar-container">
                                            <img src="{{ $teacher['avatar'] ?? asset('assets_front/images/Profile-picture.png') }}" 
                                                 alt="{{ $teacher['name'] }}" class="teacher-avatar">
                                        </div>
                                        <div class="teacher-details">
                                            <div class="teacher-name">{{ $teacher['name'] }}</div>
                                            <div class="teacher-subject">{{ $teacher['subject'] }}</div>
                                            @if($user->role_name === 'parent')
                                                <div class="student-context">For: {{ $teacher['student_context']['name'] ?? 'Unknown' }}</div>
                                            @endif
                                        </div>
                                        <button class="start-chat-button">
                                            <i class="fa-solid fa-message"></i>
                                        </button>
                                    </div>
                                @empty
                                    <div class="no-teachers">
                                        <i class="fa-solid fa-user-slash"></i>
                                        <p>No teachers available</p>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="teacher-info">
                                <div class="info-icon">
                                    <i class="fa-solid fa-chalkboard-teacher"></i>
                                </div>
                                <h6>Teacher Panel</h6>
                                <p>Students and parents can start conversations with you</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Messages -->
<div id="toastContainer" class="toast-container"></div>

@include('panel.chat.styles')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const user = @json($user);
    const userChatUid = user.role_name + '|' + user.id;
    let currentChatId = null;

    // Toast notification system
    function showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
        `;
        container.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }

    // HTML escape utility
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Display messages in chat
    function displayMessages(messages) {
        const chatContainer = document.getElementById('udChat');
        chatContainer.innerHTML = '';
        
        if (messages.length === 0) {
            chatContainer.innerHTML = `
                <div class="welcome-message">
                    <div class="welcome-icon">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                    <h5>Start the conversation</h5>
                    <p>No messages yet. Send the first message to get started!</p>
                </div>
            `;
            return;
        }
        
        messages.forEach(message => {
            const messageDiv = document.createElement('div');
            const isFromCurrentUser = message.sender === userChatUid;
            
            messageDiv.className = `message ${isFromCurrentUser ? 'from-me' : 'from-other'}`;
            
            let timeString = '';
            if (message.createdAt && message.createdAt.seconds) {
                timeString = new Date(message.createdAt.seconds * 1000).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
            
            messageDiv.innerHTML = `
                <div class="message-bubble">
                    ${escapeHtml(message.text)}
                </div>
                ${timeString ? `<div class="message-time">${timeString}</div>` : ''}
            `;
            
            chatContainer.appendChild(messageDiv);
        });
        
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Start new chat with teacher
    document.addEventListener('click', function(e) {
        if (e.target.closest('.start-chat-button')) {
            e.preventDefault();
            e.stopPropagation();
            
            const teacherItem = e.target.closest('.teacher-item');
            const teacherId = teacherItem.dataset.teacherId;
            const studentId = teacherItem.dataset.studentId || null;
            const teacherName = teacherItem.querySelector('.teacher-name').textContent;
            const teacherAvatar = teacherItem.querySelector('.teacher-avatar').src;
            
            const btn = e.target.closest('.start-chat-button');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            
            const requestData = {
                participant_id: parseInt(teacherId)
            };
            
            if (studentId) {
                requestData.student_id = parseInt(studentId);
            }
            
            fetch('{{ route("chat.start") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    showToast('Chat started successfully!', 'success');
                    
                    // Set up the chat interface
                    currentChatId = result.chatId;
                    document.getElementById('currentChatId').value = result.chatId;
                    
                    // Show chat interface
                    document.getElementById('chatHeader').style.display = 'block';
                    document.getElementById('chatBox').style.display = 'block';
                    
                    // Update header
                    document.getElementById('chatParticipantName').textContent = teacherName;
                    document.getElementById('chatAvatar').src = teacherAvatar;
                    
                    // Show welcome message
                    displayMessages([]);
                    
                    // Update button state
                    btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                    btn.classList.add('active');
                    btn.disabled = true;
                    
                    // Focus on input
                    setTimeout(() => {
                        document.getElementById('messageInput').focus();
                    }, 500);
                    
                } else {
                    throw new Error(result.error || 'Unknown error occurred');
                }
            })
            .catch(error => {
                showToast('Error starting chat: ' + error.message, 'error');
                btn.disabled = false;
                btn.innerHTML = originalContent;
            });
        }
    });

    // Load existing chat
    document.addEventListener('click', function(e) {
        if (e.target.closest('.chat-thread')) {
            const chatThread = e.target.closest('.chat-thread');
            const chatId = chatThread.dataset.chatId;
            
            // Remove active from all threads
            document.querySelectorAll('.chat-thread').forEach(thread => {
                thread.classList.remove('active');
            });
            
            // Add active to clicked thread
            chatThread.classList.add('active');
            
            // Set current chat
            currentChatId = chatId;
            document.getElementById('currentChatId').value = chatId;
            
            // Show chat interface
            document.getElementById('chatHeader').style.display = 'block';
            document.getElementById('chatBox').style.display = 'block';
            
            // Update header info
            const threadName = chatThread.querySelector('.thread-name').textContent;
            const threadAvatar = chatThread.querySelector('.thread-avatar').src;
            
            document.getElementById('chatParticipantName').textContent = threadName;
            document.getElementById('chatAvatar').src = threadAvatar;
            
            // Load messages
            const chatContainer = document.getElementById('udChat');
            chatContainer.innerHTML = '<div class="text-center p-4"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><p class="mt-2">Loading messages...</p></div>';
            
            fetch(`{{ url('/panel/chat') }}/${chatId}/messages`)
                .then(response => response.json())
                .then(messages => {
                    displayMessages(messages);
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    chatContainer.innerHTML = `
                        <div class="welcome-message">
                            <div class="welcome-icon">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                            </div>
                            <h5>Error loading messages</h5>
                            <p>Please try again or refresh the page</p>
                        </div>
                    `;
                });
        }
    });

    // Send message
    const messageForm = document.getElementById('messageForm');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message || !currentChatId) {
                if (!currentChatId) {
                    showToast('Please select a chat first', 'error');
                }
                return;
            }
            
            // Disable input
            messageInput.disabled = true;
            const submitBtn = messageForm.querySelector('button[type="submit"]');
            const originalBtnContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            
            fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    chat_id: currentChatId,
                    message: message,
                    type: 'text'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    // Add message to UI immediately
                    const chatContainer = document.getElementById('udChat');
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message from-me';
                    messageDiv.innerHTML = `
                        <div class="message-bubble">
                            ${escapeHtml(message)}
                        </div>
                        <div class="message-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</div>
                    `;
                    chatContainer.appendChild(messageDiv);
                    
                    // Clear input and scroll
                    messageInput.value = '';
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                    
                    showToast('Message sent!', 'success');
                } else {
                    throw new Error(result.error || 'Failed to send message');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                showToast('Error sending message: ' + error.message, 'error');
            })
            .finally(() => {
                // Re-enable input
                messageInput.disabled = false;
                submitBtn.innerHTML = originalBtnContent;
                messageInput.focus();
            });
        });
    }

    // Auto-refresh messages every 5 seconds for active chat
    setInterval(() => {
        if (currentChatId) {
            fetch(`{{ url('/panel/chat') }}/${currentChatId}/messages`)
                .then(response => response.json())
                .then(messages => {
                    displayMessages(messages);
                })
                .catch(error => {
                    console.error('Error refreshing messages:', error);
                });
        }
    }, 5000);

    console.log('Modern chat system initialized successfully');
});
</script>
@endsection