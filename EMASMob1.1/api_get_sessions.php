<?php
// api_get_sessions.php
// GET: Returns all active (or recent) sessions for the dashboard sidebar
// Includes: user name, last message preview, unread count, updated_at

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db_config.php';

$conn = getDB();

$status = $_GET['status'] ?? 'active'; // 'active' | 'all'
$whereStatus = ($status === 'all') ? "" : "WHERE cs.status = 'active'";

$sql = "
    SELECT 
        cs.id,
        cs.status,
        cs.created_at,
        cs.updated_at,
        u.name AS user_name,
        u.student_id,
        (
            SELECT message FROM chat_messages 
            WHERE session_id = cs.id 
            ORDER BY created_at DESC LIMIT 1
        ) AS last_message,
        (
            SELECT COUNT(*) FROM chat_messages 
            WHERE session_id = cs.id 
              AND sender_type = 'user' 
              AND is_read = 0
        ) AS unread
    FROM chat_sessions cs
    JOIN users u ON u.id = cs.user_id
    $whereStatus
    ORDER BY cs.updated_at DESC
";

$result = $conn->query($sql);
$sessions = [];

while ($row = $result->fetch_assoc()) {
    $sessions[] = $row;
}

echo json_encode(['sessions' => $sessions]);
$conn->close();
?>
