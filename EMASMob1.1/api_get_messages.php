<?php
// api_get_messages.php
// GET: Fetch messages for a session, optionally only after a certain message ID (for polling)
// Params: session_id (required), after_id (optional, for polling new messages only)
// Returns: JSON array of messages

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db_config.php';

$session_id = intval($_GET['session_id'] ?? 0);
$after_id   = intval($_GET['after_id'] ?? 0); // For polling: only get messages newer than this

if (!$session_id) {
    http_response_code(400);
    echo json_encode(['error' => 'session_id is required.']);
    exit;
}

$conn = getDB();

if ($after_id > 0) {
    $stmt = $conn->prepare("
        SELECT id, sender_type, sender_name, message, created_at
        FROM chat_messages
        WHERE session_id = ? AND id > ?
        ORDER BY created_at ASC
    ");
    $stmt->bind_param("ii", $session_id, $after_id);
} else {
    // Initial load: get last 50 messages
    $stmt = $conn->prepare("
        SELECT id, sender_type, sender_name, message, created_at
        FROM chat_messages
        WHERE session_id = ?
        ORDER BY created_at ASC
        LIMIT 50
    ");
    $stmt->bind_param("i", $session_id);
}

$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Mark user messages as read if responder is fetching (basic read receipt)
if (!empty($messages) && isset($_GET['mark_read']) && $_GET['mark_read'] === '1') {
    $upd = $conn->prepare("UPDATE chat_messages SET is_read = 1 WHERE session_id = ? AND sender_type = 'user'");
    $upd->bind_param("i", $session_id);
    $upd->execute();
    $upd->close();
}

echo json_encode(['messages' => $messages]);

$stmt->close();
$conn->close();
?>
