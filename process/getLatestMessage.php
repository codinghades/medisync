<?php
session_start();
include '../config/database.php';

$response = [];

// Check if checking for the last admin message (user side)
if (isset($_GET['check_admin_last']) && $_GET['check_admin_last'] == 'true' && isset($_GET['session_id']) && isset($_SESSION['user_id'])) {
    $sessionId = $_GET['session_id'];
    $queryLastMessage = "SELECT sender FROM messages WHERE chat_session_id = ? ORDER BY timestamp DESC LIMIT 1";
    $stmtLastMessage = $conn->prepare($queryLastMessage);
    $stmtLastMessage->bind_param("s", $sessionId);
    $stmtLastMessage->execute();
    $resultLastMessage = $stmtLastMessage->get_result();

    $response['last_admin_message'] = false;
    if ($rowLastMessage = $resultLastMessage->fetch_assoc()) {
        if (strpos($rowLastMessage['sender'], 'A-') === 0) {
            $response['last_admin_message'] = true;
        }
    }
    $stmtLastMessage->close();
}
// Else, load the latest messages for the admin interface
elseif (isset($_SESSION["user_id"])) { // Check if someone is logged in (could be admin or user)
    $response['sessions'] = [];

    // Fetch all distinct chat session IDs and the timestamp of the latest message
    $querySessions = "
        SELECT
            cs.chat_session_id,
            (SELECT m.timestamp FROM messages m WHERE m.chat_session_id = cs.chat_session_id ORDER BY m.timestamp DESC LIMIT 1) AS latest_message_timestamp,
            cs.user_id AS other_user_id -- To identify the other user
        FROM chat_sessions cs
        ORDER BY latest_message_timestamp DESC
    ";
    $stmtSessions = $conn->prepare($querySessions);
    $stmtSessions->execute();
    $resultSessions = $stmtSessions->get_result();

    $sessionIds = [];
    $sessionUserMap = [];
    while ($rowSession = $resultSessions->fetch_assoc()) {
        $sessionIds[] = $rowSession['chat_session_id'];
        $sessionUserMap[$rowSession['chat_session_id']] = $rowSession['other_user_id'];
    }
    $stmtSessions->close();

    if (!empty($sessionIds)) {
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

            $otherUserId = $sessionUserMap[$sessionId] ?? null;
            $otherUserName = 'Unknown User';

            if ($otherUserId) {
                $queryUserName = "SELECT first_name, last_name FROM users WHERE user_id = ?";
                $stmtUserName = $conn->prepare($queryUserName);
                $stmtUserName->bind_param('s', $otherUserId);
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
        $response['sessions'] = $sessionsWithLatest;
    }
} else {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>