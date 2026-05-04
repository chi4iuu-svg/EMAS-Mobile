<?php
// api_send_message.php
// POST: Send a message (from mobile user or dashboard responder)
// Required POST fields: session_id, sender_type ('user' or 'responder'), message, sender_name
// Returns: JSON { success, message_id, created_at }

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Restrict in production

require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$session_id  = intval($_POST['session_id'] ?? 0);
$sender_type = $_POST['sender_type'] ?? '';
$sender_name = trim($_POST['sender_name'] ?? '');
$message     = trim($_POST['message'] ?? '');

// Basic validation
if (!$session_id || !in_array($sender_type, ['user', 'responder']) || $message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid fields.']);
    exit;
}

$conn = getDB();

// Verify session exists
$check = $conn->prepare("SELECT id FROM chat_sessions WHERE id = ?");
$check->bind_param("i", $session_id);
$check->execute();
if ($check->get_result()->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Session not found.']);
    exit;
}
$check->close();

// Insert message
$stmt = $conn->prepare("INSERT INTO chat_messages (session_id, sender_type, sender_name, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $session_id, $sender_type, $sender_name, $message);

if ($stmt->execute()) {
    $msg_id = $stmt->insert_id;

    // Touch session updated_at
    $upd = $conn->prepare("UPDATE chat_sessions SET updated_at = NOW() WHERE id = ?");
    $upd->bind_param("i", $session_id);
    $upd->execute();
    $upd->close();

    echo json_encode([
        'success'    => true,
        'message_id' => $msg_id,
        'created_at' => date('Y-m-d H:i:s')
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message.']);
}

$stmt->close();
$conn->close();
?>
