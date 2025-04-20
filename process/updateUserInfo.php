<?php
session_start();
require '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
$password = $_POST['password'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$contactNumber = $_POST['contactNumber'] ?? '';

if (!$user_id || !$password) {
    echo json_encode(['success' => false, 'message' => 'Missing user or password']);
    exit;
}

$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$row = $result->fetch_assoc();
$hashedPassword = $row['password'];

if (!password_verify($password, $hashedPassword)) {
    echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    exit;
}

$emailCheck = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$emailCheck->bind_param("ss", $email, $user_id);
$emailCheck->execute();
$emailResult = $emailCheck->get_result();

if ($emailResult->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already in use']);
    exit;
}

$update = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, contact_number = ? WHERE user_id = ?");
$update->bind_param("sssss", $firstName, $lastName, $email, $contactNumber, $user_id);
$success = $update->execute();

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed']);
}
exit;
?>
