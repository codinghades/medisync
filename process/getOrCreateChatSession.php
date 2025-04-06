<?php
session_start();
include '../config/database.php'; // Adjust path as needed

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$admin_id = 'A-2025-0001'; // Set the fixed admin ID

// Check if a chat session exists for this user
$queryCheckSession = "SELECT chat_session_id FROM chat_sessions WHERE user_id = ? LIMIT 1";
$stmtCheckSession = $conn->prepare($queryCheckSession);
$stmtCheckSession->bind_param("s", $user_id);
$stmtCheckSession->execute();
$resultCheckSession = $stmtCheckSession->get_result();

if ($rowSession = $resultCheckSession->fetch_assoc()) {
    // Session exists
    echo json_encode(['session_id' => $rowSession['chat_session_id']]);
} else {
    // No session exists, create a new one
    $queryCreateSession = "INSERT INTO chat_sessions (user_id, admin_id, created_at) VALUES (?, ?, NOW())";
    $stmtCreateSession = $conn->prepare($queryCreateSession);
    $stmtCreateSession->bind_param("ss", $user_id, $admin_id); // Bind both user_id and admin_id
    if ($stmtCreateSession->execute()) {
        $newSessionId = $conn->insert_id;
        echo json_encode(['session_id' => $newSessionId]);
    } else {
        echo json_encode(['error' => 'Failed to create new session: ' . $stmtCreateSession->error]);
    }
    $stmtCreateSession->close();
}
$stmtCheckSession->close();
$conn->close();
?>