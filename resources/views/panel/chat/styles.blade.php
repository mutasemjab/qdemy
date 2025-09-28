
<style>
/* Main Chat Wrapper */
.chat-wrapper {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    min-height: 700px;
}

.chat-header-main {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    text-align: center;
}

.chat-title {
    margin: 0;
    font-weight: 600;
    font-size: 1.5rem;
}

.chat-main-container {
    display: flex;
    height: 650px;
}

/* Chat Area (Left Side) */
.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #e9ecef;
}

.existing-chats {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem;
    max-height: 200px;
    overflow-y: auto;
}

.existing-chats h6 {
    margin: 0 0 1rem 0;
    color: #495057;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.chat-threads {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.chat-thread {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.chat-thread:hover {
    border-color: #667eea;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
}

.chat-thread.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.thread-avatar-container {
    position: relative;
    margin-right: 0.75rem;
}

.thread-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.chat-thread.active .thread-avatar {
    border-color: white;
}

.unread-indicator {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.thread-content {
    flex: 1;
    min-width: 0;
}

.thread-name {
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.thread-last-message {
    font-size: 0.8rem;
    opacity: 0.8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.thread-time {
    font-size: 0.7rem;
    opacity: 0.7;
}

/* Active Chat Area */
.active-chat {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.active-chat-header {
    background: white;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

.chat-participant-info {
    display: flex;
    align-items: center;
}

.participant-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 1rem;
    border: 2px solid #e9ecef;
}

.participant-name {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
}

.participant-status {
    color: #28a745;
    font-size: 0.85rem;
}

.chat-messages {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.welcome-message {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.welcome-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

.welcome-message h5 {
    margin-bottom: 0.5rem;
    color: #495057;
}

.welcome-message p {
    margin: 0;
    font-size: 0.95rem;
}

/* Message Styles */
.message {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    animation: messageAppear 0.3s ease;
}

@keyframes messageAppear {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message.from-me {
    align-items: flex-end;
}

.message.from-other {
    align-items: flex-start;
}

.message-bubble {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 18px;
    position: relative;
    word-wrap: break-word;
    line-height: 1.4;
}

.message.from-me .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.message.from-other .message-bubble {
    background: white;
    color: #495057;
    border: 1px solid #e9ecef;
}

.message-time {
    font-size: 0.7rem;
    margin-top: 0.25rem;
    opacity: 0.7;
}

/* Message Input */
.message-input-area {
    background: white;
    border-top: 1px solid #e9ecef;
    padding: 1rem 1.5rem;
}

.input-container {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border-radius: 25px;
    padding: 0.5rem;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.input-container:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.message-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 0.5rem 1rem;
    font-size: 0.95rem;
    outline: none;
}

.message-input::placeholder {
    color: #adb5bd;
}

.send-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.send-button:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.send-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Teachers Panel (Right Side) */
.teachers-panel {
    width: 350px;
    background: #f8f9fa;
    border-left: 1px solid #e9ecef;
    display: flex;
    flex-direction: column;
}

.teachers-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    background: white;
}

.teachers-header h6 {
    margin: 0;
    color: #495057;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.teachers-list {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
}

.teacher-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    margin-bottom: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
}

.teacher-item:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.teacher-avatar-container {
    margin-right: 1rem;
}

.teacher-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.teacher-details {
    flex: 1;
    min-width: 0;
}

.teacher-name {
    font-weight: 600;
    font-size: 0.95rem;
    color: #495057;
    margin-bottom: 0.25rem;
}

.teacher-subject {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.student-context {
    font-size: 0.75rem;
    color: #17a2b8;
    font-style: italic;
}

.start-chat-button {
    background: #667eea;
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.start-chat-button:hover {
    background: #5a6fd8;
    transform: scale(1.1);
}

.start-chat-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.start-chat-button.active {
    background: #28a745;
}

.no-teachers {
    text-align: center;
    padding: 2rem 1rem;
    color: #6c757d;
}

.no-teachers i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.teacher-info {
    text-align: center;
    padding: 2rem 1rem;
    color: #6c757d;
}

.info-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.3;
}

.teacher-info h6 {
    margin-bottom: 0.5rem;
    color: #495057;
}

/* Toast Messages */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast {
    background: white;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    margin-bottom: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-left: 4px solid #667eea;
    animation: toastSlide 0.3s ease;
}

.toast.success {
    border-left-color: #28a745;
}

.toast.error {
    border-left-color: #dc3545;
}

@keyframes toastSlide {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .chat-main-container {
        flex-direction: column;
        height: auto;
    }
    
    .teachers-panel {
        width: 100%;
        max-height: 300px;
    }
    
    .existing-chats {
        max-height: 150px;
    }
    
    .chat-messages {
        min-height: 300px;
    }
}

/* Scrollbar Styling */
.existing-chats::-webkit-scrollbar,
.teachers-list::-webkit-scrollbar,
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.existing-chats::-webkit-scrollbar-track,
.teachers-list::-webkit-scrollbar-track,
.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.existing-chats::-webkit-scrollbar-thumb,
.teachers-list::-webkit-scrollbar-thumb,
.chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.existing-chats::-webkit-scrollbar-thumb:hover,
.teachers-list::-webkit-scrollbar-thumb:hover,
.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>