<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Check if admin is logged in (adjust logic as needed for your admin authentication)
if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'error' => 'Admin not logged in']);
    exit;
}

// Decode the JSON data
$data = json_decode(file_get_contents("php://input"), true);
if (empty($data['message']) || !isset($data['session_id'])) {
    echo json_encode(['success' => false, 'error' => 'Message cannot be empty and session ID is required']);
    exit;
}

$admin_id = $_SESSION["user_id"];
$message = $data['message'];
$chat_session_id = $data['session_id'];

// Check if the chat session exists
$checkSessionQuery = "SELECT chat_session_id FROM chat_sessions WHERE chat_session_id = ?";
$checkSessionStmt = $conn->prepare($checkSessionQuery);
$checkSessionStmt->bind_param("i", $chat_session_id);
$checkSessionStmt->execute();
$checkSessionResult = $checkSessionStmt->get_result();

if ($checkSessionResult->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid chat session ID']);
    $checkSessionStmt->close();
    $conn->close();
    exit;
}

$checkSessionStmt->close();

// Insert the admin message
$insertMessage = "INSERT INTO messages (chat_session_id, sender, message_content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insertMessage);
$stmt->bind_param("iss", $chat_session_id, $admin_id, $message);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => [
            'chat_session_id' => $chat_session_id,
            'sender' => $admin_id,
            'message' => $message,
            'timestamp' => date("F j, Y at h:i A") // 12hr format
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to send message: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>