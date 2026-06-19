<?php
// api_send_message.php
// POST: Send a message (from mobile user or dashboard responder)
// Required POST fields: session_id, sender_type ('user' or 'responder'), message, sender_name
// Optional: attachment (file upload)
// Returns: JSON { success, message_id, created_at, attachment_path }

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

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

// Must have session + sender, and at least a message OR a file
$hasFile = isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK;

if (!$session_id || !in_array($sender_type, ['user', 'responder'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid fields.']);
    exit;
}
if ($message === '' && !$hasFile) {
    http_response_code(400);
    echo json_encode(['error' => 'Message or attachment is required.']);
    exit;
}

// ── Handle file upload ────────────────────────────────────────────
$attachment_path = null;

if ($hasFile) {
    $allowed_mime = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo',
    ];
    $max_size = 20 * 1024 * 1024; // 20 MB

    $file     = $_FILES['attachment'];
    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mime     = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_mime)) {
        http_response_code(400);
        echo json_encode(['error' => 'File type not allowed. Use images or videos.']);
        exit;
    }
    if ($file['size'] > $max_size) {
        http_response_code(400);
        echo json_encode(['error' => 'File too large. Maximum size is 20 MB.']);
        exit;
    }

    // Save to uploads/chat/ folder (create if missing)
    $upload_dir = __DIR__ . '/uploads/chat/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Unique filename: timestamp + random + original extension
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $dest     = $upload_dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save file.']);
        exit;
    }

    $attachment_path = 'uploads/chat/' . $filename;
}

// ── Insert message ────────────────────────────────────────────────
$conn = getDB();

$check = $conn->prepare("SELECT id FROM chat_sessions WHERE id = ?");
$check->bind_param("i", $session_id);
$check->execute();
if ($check->get_result()->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Session not found.']);
    exit;
}
$check->close();

$stmt = $conn->prepare("INSERT INTO chat_messages (session_id, sender_type, sender_name, message, attachment_path) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $session_id, $sender_type, $sender_name, $message, $attachment_path);

if ($stmt->execute()) {
    $msg_id = $stmt->insert_id;

    $upd = $conn->prepare("UPDATE chat_sessions SET updated_at = NOW() WHERE id = ?");
    $upd->bind_param("i", $session_id);
    $upd->execute();
    $upd->close();

    echo json_encode([
        'success'         => true,
        'message_id'      => $msg_id,
        'created_at'      => date('Y-m-d H:i:s'),
        'attachment_path' => $attachment_path,
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message.']);
}

$stmt->close();
$conn->close();
?>
