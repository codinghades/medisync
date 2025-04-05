<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$response = ['messages' => []];

// Retrieve the role and admin ID (if applicable) from the database for the logged-in user
$queryUserRole = "
    SELECT user_id, role 
    FROM users 
    WHERE user_id = ?";

$stmt = $conn->prepare($queryUserRole);
$stmt->bind_param('s', $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();

$user = null;
if ($row = $result->fetch_assoc()) {
    $user = $row;
}

$stmt->close();

// If no user found or role is missing, exit
if (!$user || !$user['role']) {
    echo json_encode(['error' => 'User role not found']);
    exit;
}

$user_role = $user['role'];
$admin_id = null;

// If the logged-in user is an admin, retrieve the admin ID (or simply use the logged-in user's ID for both roles)
if ($user_role === 'admin') {
    $admin_id = $user['user_id'];  // Admin ID is the logged-in user's ID
}

// Retrieve chat session ids based on role
$sessionIds = getChatSessions($conn, $_SESSION["user_id"], $admin_id);

if (empty($sessionIds)) {
    echo json_encode($response);
    exit;
}

// Create placeholders for the SQL query based on the session IDs count
$placeholders = implode(',', array_fill(0, count($sessionIds), '?'));

// Construct the full SQL query with dynamic placeholders
$queryMessages = "
    SELECT M.message_id, M.message_content, 
           DATE_FORMAT(M.timestamp, '%M %d, %Y at %H:%i') AS formatted_timestamp, 
           M.sender, M.chat_session_id, 
           U.first_name, U.last_name
    FROM messages M
    JOIN users U ON M.sender = U.user_id
    WHERE M.chat_session_id IN ($placeholders)
    ORDER BY M.timestamp DESC";

// Prepare the query
$stmt = $conn->prepare($queryMessages);

// Dynamically bind parameters using the correct types
$types = str_repeat('s', count($sessionIds)); // 's' for each string parameter
$stmt->bind_param($types, ...$sessionIds); // Use spread operator to bind the array elements as separate arguments

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results
while ($row = $result->fetch_assoc()) {
    $response['messages'][] = [
        'message_id' => $row['message_id'],
        'message' => $row['message_content'],
        'timestamp' => $row['formatted_timestamp'],
        'sender_name' => $row['first_name'] . ' ' . $row['last_name'],
        'sender' => $row['sender'],
        'chat_session_id' => $row['chat_session_id']
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);

// Function to retrieve chat sessions based on user or admin role
function getChatSessions($conn, $user_id, $admin_id) {
    // Admin can see all chat sessions they are part of
    if ($admin_id) {
        $querySession = "
            SELECT chat_session_id 
            FROM chat_sessions 
            WHERE admin_id = ?";
        $stmt = $conn->prepare($querySession);
        $stmt->bind_param('s', $admin_id);
    } else { // Patient can only see their own chat sessions
        $querySession = "
            SELECT chat_session_id 
            FROM chat_sessions 
            WHERE user_id = ?";
        $stmt = $conn->prepare($querySession);
        $stmt->bind_param('s', $user_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $sessionIds = [];
    while ($row = $result->fetch_assoc()) {
        $sessionIds[] = $row['chat_session_id'];
    }

    $stmt->close();

    return $sessionIds;
}
?>
