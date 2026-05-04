<?php
// Report.php — Mobile user emergency chat screen
// Requires: user must be logged in (session)
// In production, get $user_id and $user_name from $_SESSION

session_start();

// For testing — replace with real session data after login is implemented
$user_id   = $_SESSION['user_id']   ?? 1;
$user_name = $_SESSION['user_name'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Emergency - EMAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0093E9 0%, #002b4d 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: white;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .main-card {
            background: linear-gradient(180deg, #0056b3 0%, #000814 100%);
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
            flex: 1;
            box-shadow: 0 -10px 25px rgba(0,0,0,0.3);
            padding: 20px 20px 100px 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .bubble-user {
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-radius: 22px 22px 5px 22px;
            padding: 12px 18px;
            max-width: 78%;
            align-self: flex-end;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            word-break: break-word;
        }
        .bubble-bot {
            background: #e5e7eb;
            color: #1f2937;
            border-radius: 22px 22px 22px 5px;
            padding: 12px 18px;
            max-width: 78%;
            align-self: flex-start;
            position: relative;
            word-break: break-word;
        }
        .bubble-wrapper-bot {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            align-self: flex-start;
        }
        .bot-avatar {
            width: 30px;
            height: 30px;
            min-width: 30px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0056b3;
            font-size: 13px;
        }
        .timestamp {
            font-size: 0.65rem;
            opacity: 0.55;
            margin-top: 3px;
        }
        .ts-right { text-align: right; align-self: flex-end; }
        .ts-left  { text-align: left;  align-self: flex-start; padding-left: 38px; }
        .input-container {
            position: fixed;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            width: 92%;
            max-width: 440px;
            background: white;
            border-radius: 50px;
            padding: 8px 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
            gap: 8px;
        }
        .message-input {
            border: none;
            outline: none;
            padding: 10px 6px;
            flex: 1;
            color: #1f2937;
            font-size: 14px;
            background: transparent;
        }
        .send-btn {
            background: #0056b3;
            color: white;
            border: none;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .send-btn:hover { background: #003f80; }
        .send-btn:disabled { background: #ccc; cursor: not-allowed; }
        #connection-status {
            font-size: 0.7rem;
            text-align: center;
            padding: 4px;
            opacity: 0.75;
        }
        .typing-indicator span {
            display: inline-block;
            width: 7px; height: 7px;
            background: #9ca3af;
            border-radius: 50%;
            animation: bounce 1.2s infinite;
            margin: 0 2px;
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes bounce {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-6px); }
        }
    </style>
</head>
<body>

    <div class="px-6 pt-10 pb-4">
        <div class="relative flex items-center justify-center">
            <a href="Main.php" class="absolute left-0 text-2xl hover:opacity-70">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold">Report Emergency</h1>
        </div>
        <p class="text-center text-sm opacity-80 mt-1">We're Here to Help</p>
        <div id="connection-status" class="text-green-300">● Connected</div>
    </div>

    <div class="main-card" id="chat-box">
        <div class="text-center text-xs opacity-50 py-2">Connecting to Infirmary...</div>
    </div>

    <div class="input-container">
        <input type="text" id="msg-input" placeholder="Describe your emergency..." class="message-input" autocomplete="off">
        <label for="file-input" class="text-gray-400 text-xl cursor-pointer hover:text-blue-500 px-1">
            <i class="fa-solid fa-paperclip"></i>
        </label>
        <input type="file" id="file-input" accept="image/*,video/*" class="hidden">
        <button class="send-btn" id="send-btn" onclick="sendMessage()">
            <i class="fa-solid fa-paper-plane text-sm"></i>
        </button>
    </div>

<script>
    const USER_ID   = <?= json_encode($user_id) ?>;
    const USER_NAME = <?= json_encode($user_name) ?>;

    let sessionId   = null;
    let lastMsgId   = 0;
    let pollInterval = null;
    let isSending   = false;

    // ── Init: get or create session ──────────────────────────────
    async function initSession() {
        try {
            const fd = new FormData();
            fd.append('user_id', USER_ID);
            fd.append('emergency_type', 'General Emergency');

            const res  = await fetch('api_session.php', { method: 'POST', body: fd });
            const data = await res.json();

            if (data.session_id) {
                sessionId = data.session_id;
                await loadMessages();
                startPolling();
            }
        } catch (e) {
            setStatus('● Reconnecting...', 'text-yellow-300');
            setTimeout(initSession, 3000);
        }
    }

    // ── Load all messages initially ──────────────────────────────
    async function loadMessages() {
        const res  = await fetch(`api_get_messages.php?session_id=${sessionId}`);
        const data = await res.json();
        const box  = document.getElementById('chat-box');
        box.innerHTML = '';

        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(renderMessage);
            lastMsgId = data.messages[data.messages.length - 1].id;
        }
        scrollBottom();
    }

    // ── Poll for new messages every 3 seconds ────────────────────
    function startPolling() {
        pollInterval = setInterval(async () => {
            if (!sessionId) return;
            try {
                const res  = await fetch(`api_get_messages.php?session_id=${sessionId}&after_id=${lastMsgId}`);
                const data = await res.json();

                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(renderMessage);
                    lastMsgId = data.messages[data.messages.length - 1].id;
                    scrollBottom();
                }
                setStatus('● Connected', 'text-green-300');
            } catch (e) {
                setStatus('● Reconnecting...', 'text-yellow-300');
            }
        }, 3000);
    }

    // ── Render a single message bubble ───────────────────────────
    function renderMessage(msg) {
        const box   = document.getElementById('chat-box');
        const isBot = msg.sender_type === 'responder';
        const time  = formatTime(msg.created_at);

        if (isBot) {
            box.innerHTML += `
                <div class="bubble-wrapper-bot">
                    <div class="bot-avatar"><i class="fa-solid fa-user-nurse"></i></div>
                    <div>
                        <div class="bubble-bot">${escHtml(msg.message)}</div>
                    </div>
                </div>
                <div class="timestamp ts-left">${escHtml(msg.sender_name || 'Infirmary')} · ${time}</div>`;
        } else {
            box.innerHTML += `
                <div class="bubble-user">${escHtml(msg.message)}</div>
                <div class="timestamp ts-right">${time}</div>`;
        }
    }

    // ── Send a message ────────────────────────────────────────────
    async function sendMessage() {
        const input = document.getElementById('msg-input');
        const text  = input.value.trim();
        if (!text || !sessionId || isSending) return;

        isSending = true;
        document.getElementById('send-btn').disabled = true;
        input.value = '';

        const fd = new FormData();
        fd.append('session_id', sessionId);
        fd.append('sender_type', 'user');
        fd.append('sender_name', USER_NAME);
        fd.append('message', text);

        try {
            const res  = await fetch('api_send_message.php', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                // Optimistically render immediately
                renderMessage({
                    id: data.message_id,
                    sender_type: 'user',
                    sender_name: USER_NAME,
                    message: text,
                    created_at: null // use client time
                });
                lastMsgId = data.message_id;
                scrollBottom();
            }
        } catch (e) {
            alert('Failed to send message. Please try again.');
            input.value = text; // Restore
        }

        isSending = false;
        document.getElementById('send-btn').disabled = false;
        input.focus();
    }

    // ── Enter key to send ─────────────────────────────────────────
    document.getElementById('msg-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // ── Helpers ───────────────────────────────────────────────────
    function scrollBottom() {
        const box = document.getElementById('chat-box');
        box.scrollTop = box.scrollHeight;
    }

    function setStatus(text, cls) {
        const el = document.getElementById('connection-status');
        el.textContent = text;
        el.className = `text-xs text-center py-1 ${cls}`;
    }

    function formatTime(ts) {
        // No timestamp = just use right now (for optimistically rendered messages)
        if (!ts) return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        // MySQL "YYYY-MM-DD HH:MM:SS" — replace space with T so browsers
        // treat it as local time instead of UTC.
        const d = new Date(ts.replace(' ', 'T'));
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    // ── Start ─────────────────────────────────────────────────────
    initSession();
</script>
</body>
</html>
