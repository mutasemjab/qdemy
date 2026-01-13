
<style>
.chat-wrapper{background:#fff;border:1px solid #e6e9f2;border-radius:18px;box-shadow:0 18px 40px rgba(17,24,39,.08);overflow:hidden;min-height:720px}
.chat-header-main{background:linear-gradient(135deg,#0b57d0 0%,#0055D2 100%);color:#fff;padding:18px 20px;text-align:center}
.chat-title{margin:0;font-weight:900;font-size:20px;letter-spacing:.2px}

.chat-main-container{display:flex;height:680px}

.chat-area{flex:1;display:flex;flex-direction:column;border-inline-end:1px solid #edf1f7;background:#fbfcff}
.existing-chats{background:#f6f8fd;border-bottom:1px solid #edf1f7;padding:12px;max-height:210px;overflow-y:auto}
.existing-chats h6{margin:0 0 10px;color:#334155;font-weight:900;font-size:12px;text-transform:uppercase;letter-spacing:.06em}
.chat-threads{display:flex;flex-direction:column;gap:10px}
.chat-thread{display:flex;align-items:center;gap:10px;padding:10px 12px;background:#fff;border-radius:12px;cursor:pointer;transition:transform .12s ease, box-shadow .12s ease, border-color .12s ease;border:1px solid #e6e9f2}
.chat-thread:hover{transform:translateY(-1px);box-shadow:0 10px 24px rgba(0,85,210,.12);border-color:#d9e6ff}
.chat-thread.active{background:#0b57d0;border-color:#0b57d0;box-shadow:0 12px 28px rgba(11,87,208,.25);color:#fff}
.thread-avatar-container{position:relative;flex:0 0 auto}
.thread-avatar{width:44px;height:44px;border-radius:50%;object-fit:cover;border:2px solid #edf1f7}
.chat-thread.active .thread-avatar{border-color:#fff}
.unread-indicator{position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;border-radius:999px;min-width:18px;height:18px;font-size:11px;display:flex;align-items:center;justify-content:center;font-weight:900}
.thread-content{flex:1;min-width:0}
.thread-name{font-weight:900;font-size:14px;line-height:1.1;margin:0 0 2px}
.thread-last-message{font-size:12px;opacity:.8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.thread-time{font-size:11px;opacity:.7}

.active-chat{flex:1;display:flex;flex-direction:column;overflow:hidden;min-height:0}
.active-chat-header{background:#fff;border-bottom:1px solid #edf1f7;padding:12px 16px;flex-shrink:0}
.chat-participant-info{display:flex;align-items:center;gap:12px}
.participant-avatar{width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #edf1f7}
.participant-name{margin:0;font-size:16px;font-weight:900;color:#0f172a}
.participant-status{color:#16a34a;font-size:12px;font-weight:800}

.chat-messages{flex:1;padding:18px;overflow-y:auto;background:linear-gradient(180deg,#f7f9fe 0%,#edf2ff 100%);min-height:0}
.welcome-message{text-align:center;padding:40px 10px;color:#64748b}
.welcome-icon{font-size:44px;margin-bottom:10px;opacity:.35}
.welcome-message h5{margin:0 0 6px;color:#0f172a;font-weight:900}
.welcome-message p{margin:0;font-size:13px}

.message{margin-bottom:14px;display:flex;flex-direction:column;animation:udMsg .2s ease}
@keyframes udMsg{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
.message.from-me{align-items:flex-end}
.message.from-other{align-items:flex-start}
.message-bubble{max-width:72%;padding:10px 12px;border-radius:16px;position:relative;word-wrap:break-word;line-height:1.45;border:1px solid transparent}
.message.from-me .message-bubble{background:#0055D2;color:#fff;border-color:#0049b6;box-shadow:0 10px 22px rgba(0,85,210,.22)}
.message.from-other .message-bubble{background:#fff;color:#0f172a;border-color:#e6e9f2;box-shadow:0 8px 18px rgba(17,24,39,.06)}
.message-time{font-size:11px;margin-top:4px;opacity:.65}

.message-input-area{background:#fff;border-top:1px solid #edf1f7;padding:12px 16px;flex-shrink:0}
.input-container{display:flex;align-items:center;background:#f2f5fb;border-radius:999px;padding:6px;border:1.5px solid #e6e9f2;transition:border-color .12s ease, box-shadow .12s ease}
.input-container:focus-within{border-color:#bcd3ff;box-shadow:0 0 0 3px rgba(46,108,240,.15)}
.message-input{flex:1;border:0;background:transparent;padding:8px 12px;font-size:14px;outline:0}
.message-input::placeholder{color:#94a3b8}
.send-button{background:#0b57d0;border:0;color:#fff;width:42px;height:42px;border-radius:50%;display:grid;place-items:center;cursor:pointer;transition:transform .12s ease, box-shadow .12s ease}
.send-button:hover{transform:translateY(-1px);box-shadow:0 10px 22px rgba(11,87,208,.25)}
.send-button:disabled{opacity:.55;cursor:not-allowed;transform:none;box-shadow:none}

.teachers-panel{width:360px;background:#f6f8fd;border-inline-start:1px solid #edf1f7;display:flex;flex-direction:column}
.teachers-header{padding:14px 16px;border-bottom:1px solid #edf1f7;background:#fff}
.teachers-header h6{margin:0;color:#0f172a;font-weight:900;font-size:12px;text-transform:uppercase;letter-spacing:.06em}
.teachers-list{flex:1;padding:12px;overflow-y:auto;display:grid;gap:10px}
.teacher-item{display:flex;align-items:center;gap:12px;padding:12px;background:#fff;border-radius:14px;border:1px solid #e6e9f2;transition:transform .12s ease, box-shadow .12s ease, border-color .12s ease}
.teacher-item:hover{transform:translateY(-2px);box-shadow:0 14px 28px rgba(0,85,210,.12);border-color:#d9e6ff}
.teacher-avatar-container{flex:0 0 auto}
.teacher-avatar{width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid #edf1f7}
.teacher-details{flex:1;min-width:0}
.teacher-name{font-weight:900;font-size:14px;color:#0f172a;margin:0 0 2px}
.teacher-subject{font-size:12px;color:#64748b;margin:0 0 2px}
.student-context{font-size:11px;color:#0b57d0;font-weight:800}
.start-chat-button{background:#0b57d0;border:0;color:#fff;width:42px;height:42px;border-radius:12px;display:grid;place-items:center;cursor:pointer;transition:transform .12s ease, box-shadow .12s ease, background .12s ease}
.start-chat-button:hover{transform:translateY(-1px);box-shadow:0 10px 22px rgba(11,87,208,.25)}
.start-chat-button:disabled{opacity:.55;cursor:not-allowed;transform:none;box-shadow:none}
.start-chat-button.active{background:#16a34a}

.no-teachers{text-align:center;padding:28px 10px;color:#64748b}
.no-teachers i{font-size:28px;margin-bottom:6px;opacity:.45}
.teacher-info{text-align:center;padding:28px 10px;color:#64748b}
.info-icon{font-size:42px;margin-bottom:10px;opacity:.35}
.teacher-info h6{margin-bottom:6px;color:#0f172a;font-weight:900}

.toast-container{position:fixed;top:18px;right:18px;z-index:2147483647;display:grid;gap:8px}
.toast{background:#fff;border-radius:12px;padding:12px 14px;box-shadow:0 14px 28px rgba(17,24,39,.16);border-inline-start:4px solid #0b57d0;animation:udToast .25s ease}
.toast.success{border-inline-start-color:#16a34a}
.toast.error{border-inline-start-color:#ef4444}
@keyframes udToast{from{transform:translateX(30px);opacity:0}to{transform:translateX(0);opacity:1}}

.existing-chats::-webkit-scrollbar,
.teachers-list::-webkit-scrollbar,
.chat-messages::-webkit-scrollbar{width:8px;height:8px}
.existing-chats::-webkit-scrollbar-track,
.teachers-list::-webkit-scrollbar-track,
.chat-messages::-webkit-scrollbar-track{background:#eef2f7;border-radius:999px}
.existing-chats::-webkit-scrollbar-thumb,
.teachers-list::-webkit-scrollbar-thumb,
.chat-messages::-webkit-scrollbar-thumb{background:#c8d4f5;border-radius:999px}
.existing-chats::-webkit-scrollbar-thumb:hover,
.teachers-list::-webkit-scrollbar-thumb:hover,
.chat-messages::-webkit-scrollbar-thumb:hover{background:#b3c5f2}

@media (max-width:1200px){
  .teachers-panel{width:320px}
}
@media (max-width:992px){
  .chat-main-container{flex-direction:column;height:auto}
  .teachers-panel{width:100%;max-height:340px}
  .existing-chats{max-height:160px}
  .chat-messages{min-height:360px}
}
@media (max-width:560px){
  .chat-wrapper{border-radius:14px}
  .chat-messages{padding:14px}
  .message-bubble{max-width:82%}
  .teachers-panel{max-height:320px}
}

</style>