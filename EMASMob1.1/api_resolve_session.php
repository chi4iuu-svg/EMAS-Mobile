<?php
// api_resolve_session.php
// POST: Mark a session as resolved
// POST fields: session_id

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$session_id = intval($_POST['session_id'] ?? 0);
if (!$session_id) {
    http_response_code(400);
    echo json_encode(['error' => 'session_id is required.']);
    exit;
}

$conn = getDB();
$stmt = $conn->prepare("UPDATE chat_sessions SET status = 'resolved' WHERE id = ?");
$stmt->bind_param("i", $session_id);
$stmt->execute();

echo json_encode(['success' => true]);
$stmt->close();
$conn->close();
?>
