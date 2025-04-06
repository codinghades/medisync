<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$response = ['sessions' => []];

// Fetch all distinct chat session IDs
$querySessions = "SELECT DISTINCT chat_session_id FROM chat_sessions";
$stmtSessions = $conn->prepare($querySessions);
$stmtSessions->execute();
$resultSessions = $stmtSessions->get_result();

$sessionIds = [];
while ($rowSession = $resultSessions->fetch_assoc()) {
    $sessionIds[] = $rowSession['chat_session_id'];
}
$stmtSessions->close();

if (empty($sessionIds)) {
    echo json_encode($response);
    exit;
}

$sessionsWithLatest = [];

foreach ($sessionIds as $sessionId) {
    $queryLatest = "
        SELECT
            m.message_content,
            DATE_FORMAT(m.timestamp, '%M %d, %Y at %h:%i %p') AS formatted_timestamp,
            u.first_name AS sender_first_name,
            u.last_name AS sender_last_name,
            m.sender AS sender_id
        FROM messages m
        JOIN users u ON m.sender = u.user_id
        WHERE m.chat_session_id = ?
        ORDER BY m.timestamp DESC
        LIMIT 1";
    $stmtLatest = $conn->prepare($queryLatest);
    $stmtLatest->bind_param('s', $sessionId);
    $stmtLatest->execute();
    $resultLatest = $stmtLatest->get_result();
    $latest = $resultLatest->fetch_assoc();
    $stmtLatest->close();

    $sessionsWithLatest[] = [
        'chat_session_id' => $sessionId,
        'latest_message' => $latest['message_content'] ?? null,
        'latest_timestamp' => $latest['formatted_timestamp'] ?? null,
        'sender_name' => ($latest['sender_first_name'] ?? '') . ' ' . ($latest['sender_last_name'] ?? ''),
        'sender_id' => $latest['sender_id'] ?? null,
    ];
}

$conn->close();

$response['sessions'] = $sessionsWithLatest;

header('Content-Type: application/json');
echo json_encode($response);
?>