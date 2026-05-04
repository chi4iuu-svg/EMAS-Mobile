<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMAS — Infirmary Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #0b0f1a;
            --surface:  #111827;
            --border:   #1f2937;
            --accent:   #2563eb;
            --accent2:  #0ea5e9;
            --danger:   #ef4444;
            --success:  #22c55e;
            --text:     #f1f5f9;
            --muted:    #64748b;
            --user-bubble:    #2563eb;
            --staff-bubble:   #1f2937;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ── Top Bar ─────────────────────────────────────────── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .topbar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.02em;
        }
        .topbar-logo .badge {
            background: var(--accent);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            letter-spacing: 0.05em;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 0.85rem;
            color: var(--muted);
        }
        #global-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
        }
        .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--success); animation: blink 2s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

        /* ── Main Layout ─────────────────────────────────────── */
        .main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ── Sidebar ─────────────────────────────────────────── */
        .sidebar {
            width: 300px;
            min-width: 260px;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .sidebar-header {
            padding: 16px 20px 12px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-header h2 {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
        }
        #session-count {
            display: inline-block;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 999px;
            margin-left: 6px;
        }
        .session-list {
            flex: 1;
            overflow-y: auto;
        }
        .session-item {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: background 0.15s;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .session-item:hover { background: rgba(255,255,255,0.04); }
        .session-item.active { background: rgba(37,99,235,0.15); border-left: 3px solid var(--accent); }
        .session-avatar {
            width: 38px;
            height: 38px;
            min-width: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
        }
        .session-info { flex: 1; min-width: 0; }
        .session-name { font-weight: 600; font-size: 0.9rem; }
        .session-preview {
            font-size: 0.75rem;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }
        .session-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }
        .session-time { font-size: 0.65rem; color: var(--muted); font-family: 'DM Mono', monospace; }
        .unread-badge {
            background: var(--danger);
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 999px;
        }
        .empty-sessions {
            padding: 40px 20px;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
        }

        /* ── Chat Panel ──────────────────────────────────────── */
        .chat-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-header {
            padding: 16px 24px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .chat-header-left { display: flex; align-items: center; gap: 12px; }
        .chat-user-name { font-weight: 700; font-size: 1rem; }
        .chat-status-tag {
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: 0.04em;
        }
        .tag-active   { background: rgba(34,197,94,0.15);  color: var(--success); }
        .tag-resolved { background: rgba(100,116,139,0.15); color: var(--muted); }
        .resolve-btn {
            background: rgba(34,197,94,0.1);
            color: var(--success);
            border: 1px solid rgba(34,197,94,0.3);
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .resolve-btn:hover { background: rgba(34,197,94,0.2); }

        .messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            scroll-behavior: smooth;
        }
        .messages-area::-webkit-scrollbar { width: 4px; }
        .messages-area::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        /* Bubbles */
        .msg-row { display: flex; flex-direction: column; }
        .msg-row.from-user { align-items: flex-start; }
        .msg-row.from-staff { align-items: flex-end; }

        .bubble {
            max-width: 62%;
            padding: 11px 16px;
            border-radius: 18px;
            font-size: 0.9rem;
            line-height: 1.5;
            word-break: break-word;
        }
        .bubble-user-dash {
            background: rgba(255,255,255,0.08);
            border: 1px solid var(--border);
            border-radius: 4px 18px 18px 18px;
        }
        .bubble-staff-dash {
            background: var(--accent);
            color: white;
            border-radius: 18px 4px 18px 18px;
        }
        .msg-meta {
            font-size: 0.65rem;
            color: var(--muted);
            margin-top: 3px;
            font-family: 'DM Mono', monospace;
        }

        .day-divider {
            text-align: center;
            font-size: 0.7rem;
            color: var(--muted);
            padding: 10px 0;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Input */
        .input-bar {
            padding: 16px 24px;
            background: var(--surface);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .dash-input {
            flex: 1;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--text);
            font-size: 0.9rem;
            outline: none;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s;
        }
        .dash-input:focus { border-color: var(--accent); }
        .dash-input::placeholder { color: var(--muted); }
        .dash-send {
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dash-send:hover { background: #1d4ed8; }
        .dash-send:disabled { background: var(--border); cursor: not-allowed; }

        /* Empty state */
        .no-chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            gap: 12px;
        }
        .no-chat i { font-size: 3rem; opacity: 0.3; }
        .no-chat p { font-size: 0.9rem; }

        /* Scrollbar */
        .session-list::-webkit-scrollbar { width: 4px; }
        .session-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-logo">
        <i class="fa-solid fa-shield-heart" style="color: var(--accent2); font-size: 1.3rem;"></i>
        EMAS Dashboard
        <span class="badge">INFIRMARY</span>
    </div>
    <div class="topbar-right">
        <div id="global-status"><div class="dot"></div> Live</div>
        <span>University Infirmary Staff</span>
        <i class="fa-solid fa-right-from-bracket" style="cursor:pointer;" title="Logout" onclick="window.location='logout.php'"></i>
    </div>
</div>

<!-- Main -->
<div class="main">

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Active Sessions <span id="session-count">0</span></h2>
        </div>
        <div class="session-list" id="session-list">
            <div class="empty-sessions">
                <i class="fa-solid fa-inbox" style="font-size:1.5rem; display:block; margin-bottom:8px; opacity:0.4;"></i>
                No active emergencies
            </div>
        </div>
    </div>

    <!-- Chat Panel -->
    <div class="chat-panel" id="chat-panel">
        <div class="no-chat" id="no-chat-state">
            <i class="fa-solid fa-comment-medical"></i>
            <p>Select an emergency to respond</p>
        </div>

        <!-- Active chat (hidden until session selected) -->
        <div id="active-chat" style="display:none; flex:1; flex-direction:column; overflow:hidden;">
            <div class="chat-header">
                <div class="chat-header-left">
                    <div class="session-avatar" id="hdr-avatar">?</div>
                    <div>
                        <div class="chat-user-name" id="hdr-name">—</div>
                        <div class="chat-status-tag tag-active" id="hdr-tag">ACTIVE</div>
                    </div>
                </div>
                <button class="resolve-btn" onclick="resolveSession()">
                    <i class="fa-solid fa-check"></i> Mark Resolved
                </button>
            </div>
            <div class="messages-area" id="messages-area"></div>
            <div class="input-bar">
                <input type="text" class="dash-input" id="staff-input" placeholder="Type your response..." autocomplete="off">
                <label id="staff-file-label" for="staff-file-input" title="" style="cursor:pointer;color:var(--muted);font-size:1.1rem;padding:4px 6px;transition:color 0.2s;" title="Attach image or video">
                    <i class="fa-solid fa-paperclip"></i>
                </label>
                <input type="file" id="staff-file-input" accept="image/*,video/*" style="display:none;">
                <button class="dash-send" id="staff-send" onclick="sendStaffMessage()">
                    <i class="fa-solid fa-paper-plane"></i> Send
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    const STAFF_NAME = 'University Infirmary';

    let sessions       = {};   // sessionId -> { user_name, lastMsgId, ... }
    let activeSession  = null;
    let pollInterval   = null;

    // ── Fetch all active sessions ─────────────────────────────────
    async function fetchSessions() {
        try {
            const res  = await fetch('api_get_sessions.php');
            const data = await res.json();

            if (data.sessions) {
                renderSidebarSessions(data.sessions);
            }
        } catch (e) { /* silent */ }
    }

    function renderSidebarSessions(list) {
        const el    = document.getElementById('session-list');
        const count = document.getElementById('session-count');
        count.textContent = list.length;

        if (list.length === 0) {
            el.innerHTML = `<div class="empty-sessions">
                <i class="fa-solid fa-inbox" style="font-size:1.5rem;display:block;margin-bottom:8px;opacity:0.4;"></i>
                No active emergencies</div>`;
            return;
        }

        el.innerHTML = '';
        list.forEach(s => {
            // Preserve lastMsgId if we already know this session
            if (!sessions[s.id]) sessions[s.id] = { lastMsgId: 0 };
            sessions[s.id].user_name = s.user_name;
            sessions[s.id].status    = s.status;

            const initials = (s.user_name || 'U').split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();
            const isActive = activeSession == s.id;
            const preview  = escHtml(s.last_message || 'No messages yet');
            const time     = formatTime(s.updated_at);

            el.innerHTML += `
                <div class="session-item ${isActive ? 'active' : ''}" onclick="openSession(${s.id})">
                    <div class="session-avatar">${initials}</div>
                    <div class="session-info">
                        <div class="session-name">${escHtml(s.user_name || 'Unknown')}</div>
                        <div class="session-preview">${preview}</div>
                    </div>
                    <div class="session-meta">
                        <span class="session-time">${time}</span>
                        ${s.unread > 0 ? `<span class="unread-badge">${s.unread}</span>` : ''}
                    </div>
                </div>`;
        });
    }

    // ── Open a session ────────────────────────────────────────────
    async function openSession(sessionId) {
        activeSession = sessionId;
        const s = sessions[sessionId] || {};

        document.getElementById('no-chat-state').style.display  = 'none';
        document.getElementById('active-chat').style.display    = 'flex';

        const initials = (s.user_name || 'U').split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();
        document.getElementById('hdr-avatar').textContent = initials;
        document.getElementById('hdr-name').textContent   = s.user_name || 'Unknown';
        document.getElementById('hdr-tag').textContent    = (s.status || 'active').toUpperCase();

        await loadSessionMessages(sessionId);
        fetchSessions(); // Refresh sidebar to clear unread badge
    }

    async function loadSessionMessages(sessionId) {
        const res  = await fetch(`api_get_messages.php?session_id=${sessionId}&mark_read=1`);
        const data = await res.json();
        const area = document.getElementById('messages-area');
        area.innerHTML = '';

        if (data.messages && data.messages.length > 0) {
            data.messages.forEach(m => renderBubble(m));
            sessions[sessionId].lastMsgId = data.messages[data.messages.length - 1].id;
        }
        scrollBottom();
    }

    // ── Poll active session for new messages ──────────────────────
    function startPolling() {
        pollInterval = setInterval(async () => {
            await fetchSessions();

            if (!activeSession) return;
            const lastId = sessions[activeSession]?.lastMsgId || 0;

            try {
                const res  = await fetch(`api_get_messages.php?session_id=${activeSession}&after_id=${lastId}&mark_read=1`);
                const data = await res.json();
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(m => renderBubble(m));
                    sessions[activeSession].lastMsgId = data.messages[data.messages.length - 1].id;
                    scrollBottom();
                }
            } catch (e) { /* silent */ }
        }, 3000);
    }

    // ── Render attachment HTML inside a bubble ────────────────────
    function renderAttachment(path) {
        if (!path) return '';
        const ext = path.split('.').pop().toLowerCase();
        const isVideo = ['mp4','webm','mov','avi'].includes(ext);
        if (isVideo) {
            return `<video src="${path}" controls style="max-width:100%;border-radius:8px;margin-top:6px;display:block;"></video>`;
        }
        return `<a href="${path}" target="_blank"><img src="${path}" style="max-width:100%;border-radius:8px;margin-top:6px;display:block;cursor:pointer;" loading="lazy"></a>`;
    }

    // ── Render bubble ─────────────────────────────────────────────
    function renderBubble(msg) {
        const area    = document.getElementById('messages-area');
        const isStaff = msg.sender_type === 'responder';
        const time    = formatTime(msg.created_at);
        const att     = renderAttachment(msg.attachment_path);
        const txt     = msg.message ? `<div>${escHtml(msg.message)}</div>` : '';

        area.innerHTML += `
            <div class="msg-row ${isStaff ? 'from-staff' : 'from-user'}">
                <div class="bubble ${isStaff ? 'bubble-staff-dash' : 'bubble-user-dash'}">${txt}${att}</div>
                <div class="msg-meta">${escHtml(isStaff ? 'You' : (msg.sender_name || 'Student'))} · ${time}</div>
            </div>`;
    }

    // ── Staff file input change ───────────────────────────────────
    document.getElementById('staff-file-input').addEventListener('change', function () {
        const label = document.getElementById('staff-file-label');
        if (this.files.length) {
            label.style.color = '#60a5fa';
            label.title = this.files[0].name;
        } else {
            label.style.color = '';
            label.title = '';
        }
    });

    function clearStaffFile() {
        const fi = document.getElementById('staff-file-input');
        fi.value = '';
        const label = document.getElementById('staff-file-label');
        label.style.color = '';
        label.title = '';
    }

    // ── Send staff message ────────────────────────────────────────
    async function sendStaffMessage() {
        const input     = document.getElementById('staff-input');
        const fileInput = document.getElementById('staff-file-input');
        const text      = input.value.trim();
        const hasFile   = fileInput.files.length > 0;

        if (!text && !hasFile) return;
        if (!activeSession) return;

        document.getElementById('staff-send').disabled = true;
        const sentText = text;
        input.value = '';

        let localAttachmentUrl = null;
        if (hasFile) localAttachmentUrl = URL.createObjectURL(fileInput.files[0]);

        const fd = new FormData();
        fd.append('session_id', activeSession);
        fd.append('sender_type', 'responder');
        fd.append('sender_name', STAFF_NAME);
        fd.append('message', sentText);
        if (hasFile) fd.append('attachment', fileInput.files[0]);

        try {
            const res  = await fetch('api_send_message.php', { method: 'POST', body: fd });
            const data = await res.json();
            if (data.success) {
                renderBubble({
                    id: data.message_id,
                    sender_type: 'responder',
                    sender_name: STAFF_NAME,
                    message: sentText,
                    attachment_path: localAttachmentUrl,
                    created_at: null // use client time
                });
                sessions[activeSession].lastMsgId = data.message_id;
                scrollBottom();
                clearStaffFile();
            }
        } catch (e) {
            alert('Failed to send. Check your connection.');
            input.value = sentText;
        }

        document.getElementById('staff-send').disabled = false;
        input.focus();
    }

    // ── Resolve session ───────────────────────────────────────────
    async function resolveSession() {
        if (!activeSession || !confirm('Mark this emergency as resolved?')) return;
        const fd = new FormData();
        fd.append('session_id', activeSession);
        await fetch('api_resolve_session.php', { method: 'POST', body: fd });
        document.getElementById('hdr-tag').textContent = 'RESOLVED';
        document.getElementById('hdr-tag').className = 'chat-status-tag tag-resolved';
        fetchSessions();
    }

    document.getElementById('staff-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendStaffMessage();
        }
    });

    function scrollBottom() {
        const a = document.getElementById('messages-area');
        a.scrollTop = a.scrollHeight;
    }

    function escHtml(str) {
        if (!str) return '';
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function formatTime(ts) {
        // No timestamp = just use right now (for optimistically rendered messages)
        if (!ts) return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        // MySQL "YYYY-MM-DD HH:MM:SS" — replace space with T so browsers
        // treat it as local time instead of UTC.
        const d = new Date(ts.replace(' ', 'T'));
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // ── Boot ──────────────────────────────────────────────────────
    fetchSessions();
    startPolling();
</script>
</body>
</html>
