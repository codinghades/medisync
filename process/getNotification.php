<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION["user_id"];

$query = "SELECT title, message, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($notifications);
?>
