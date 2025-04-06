<?php
session_start();
include '../config/database.php';

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

// Decode the JSON data
$data = json_decode(file_get_contents("php://input"), true);
if (empty($data['message'])) {
    echo json_encode(['success' => false, 'error' => 'Message cannot be empty']);
    exit;
}

$user_id = $_SESSION["user_id"];
$message = $data['message'];

// Check if a chat session already exists for this user
$query = "SELECT chat_session_id FROM chat_sessions WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $chat_session_id = $row['chat_session_id'];
} else {
    // No session exists, create one and assign a default admin (or NULL if not needed)
    $default_admin_id = null; // You can set this if needed
    $insertSession = "INSERT INTO chat_sessions (user_id, admin_id) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($insertSession);
    $stmtInsert->bind_param("ss", $user_id, $default_admin_id);
    $stmtInsert->execute();
    $chat_session_id = $stmtInsert->insert_id;
    $stmtInsert->close();
}

$stmt->close();

// Insert the message
$insertMessage = "INSERT INTO messages (chat_session_id, sender, message_content) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insertMessage);
$stmt->bind_param("sss", $chat_session_id, $user_id, $message);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => [
            'chat_session_id' => $chat_session_id,
            'sender' => $user_id,
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
