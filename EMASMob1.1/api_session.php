<?php
// api_session.php
// POST: Create a new chat session for a user, or return their active one
// POST fields: user_id, emergency_type (optional)
// Returns: JSON { session_id, status, created_at }

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$user_id        = intval($_POST['user_id'] ?? 0);
$emergency_type = trim($_POST['emergency_type'] ?? '');

if (!$user_id) {
    http_response_code(400);
    echo json_encode(['error' => 'user_id is required.']);
    exit;
}

$conn = getDB();

// Check for existing active session
$stmt = $conn->prepare("SELECT id, status, created_at FROM chat_sessions WHERE user_id = ? AND status = 'active' ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'session_id' => $row['id'],
        'status'     => $row['status'],
        'created_at' => $row['created_at'],
        'new'        => false
    ]);
} else {
    // Create new session
    $ins = $conn->prepare("INSERT INTO chat_sessions (user_id, emergency_type, status) VALUES (?, ?, 'active')");
    $ins->bind_param("is", $user_id, $emergency_type);
    $ins->execute();
    $session_id = $ins->insert_id;
    $ins->close();

    // Auto-insert welcome message from responder
    $welcome = "Hello! This is the Ateneo de Zamboanga University Infirmary. Please describe your emergency and location. Help is on the way.";
    $bot = $conn->prepare("INSERT INTO chat_messages (session_id, sender_type, sender_name, message) VALUES (?, 'responder', 'University Infirmary', ?)");
    $bot->bind_param("is", $session_id, $welcome);
    $bot->execute();
    $bot->close();

    echo json_encode([
        'session_id' => $session_id,
        'status'     => 'active',
        'created_at' => date('Y-m-d H:i:s'),
        'new'        => true
    ]);
}

$stmt->close();
$conn->close();
?>
