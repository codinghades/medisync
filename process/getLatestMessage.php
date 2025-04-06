<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['error' => 'Admin not logged in']);
    exit;
}

$response = ['sessions' => []];

// Fetch all distinct chat session IDs and the timestamp of the latest message
$querySessions = "
    SELECT
        cs.chat_session_id,
        (SELECT m.timestamp FROM messages m WHERE m.chat_session_id = cs.chat_session_id ORDER BY m.timestamp DESC LIMIT 1) AS latest_message_timestamp
    FROM chat_sessions cs
    ORDER BY latest_message_timestamp DESC
";
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
            DATE_FORMAT(m.timestamp, '%Y-%m-%d %H:%i:%s') AS latest_timestamp_raw,
            m.sender AS sender_id
        FROM messages m
        WHERE m.chat_session_id = ?
        ORDER BY m.timestamp DESC
        LIMIT 1";
    $stmtLatest = $conn->prepare($queryLatest);
    $stmtLatest->bind_param('s', $sessionId);
    $stmtLatest->execute();
    $resultLatest = $stmtLatest->get_result();
    $latest = $resultLatest->fetch_assoc();
    $stmtLatest->close();

    // Get the user_id associated with this chat session
    $queryUser = "SELECT user_id FROM chat_sessions WHERE chat_session_id = ?";
    $stmtUser = $conn->prepare($queryUser);
    $stmtUser->bind_param('i', $sessionId);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userData = $resultUser->fetch_assoc();
    $stmtUser->close();

    $otherUserName = 'Unknown User';
    if ($userData && $userData['user_id']) {
        $userId = $userData['user_id'];
        $queryUserName = "SELECT first_name, last_name FROM users WHERE user_id = ?";
        $stmtUserName = $conn->prepare($queryUserName);
        $stmtUserName->bind_param('s', $userId);
        $stmtUserName->execute();
        $resultUserName = $stmtUserName->get_result();
        $userNameData = $resultUserName->fetch_assoc();
        $stmtUserName->close();
        if ($userNameData) {
            $otherUserName = $userNameData['first_name'] . ' ' . $userNameData['last_name'];
        }
    }

    $sessionsWithLatest[] = [
        'chat_session_id' => $sessionId,
        'latest_message' => $latest['message_content'] ?? null,
        'latest_timestamp' => $latest['formatted_timestamp'] ?? null,
        'other_user_name' => $otherUserName,
        'latest_sender_id' => $latest['sender_id'] ?? null,
    ];
}

$conn->close();

$response['sessions'] = $sessionsWithLatest;

header('Content-Type: application/json');
echo json_encode($response);
?>