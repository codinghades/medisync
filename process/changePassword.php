<?php
session_start();
require '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmNewPassword = $_POST['confirmNewPassword'] ?? '';

if (!$user_id || !$currentPassword || !$newPassword || !$confirmNewPassword) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

if ($newPassword !== $confirmNewPassword) {
    echo json_encode(['success' => false, 'error' => 'password_mismatch']);
    exit;
}

$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$row = $result->fetch_assoc();
$hashedPassword = $row['password'];

if (!password_verify($currentPassword, $hashedPassword)) {
    echo json_encode(['success' => false, 'error' => 'incorrect_password']);
    exit;
}

$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
$updateStmt->bind_param("ss", $newHashedPassword, $user_id);
$updateSuccess = $updateStmt->execute();

if ($updateSuccess) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password']);
}

exit;
?>
