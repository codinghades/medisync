<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION["user_id"];

$data = json_decode(file_get_contents("php://input"), true);
$totalAmount = isset($data["totalAmount"]) ? floatval($data["totalAmount"]) : 0;
$paymentMethod = isset($data["paymentMethod"]) ? $data["paymentMethod"] : "Unknown";
$selectedBills = isset($data["selectedBills"]) ? $data["selectedBills"] : [];

$currentDate = date("Y-m-d");

if (!empty($selectedBills)) {
    $placeholders = implode(',', array_fill(0, count($selectedBills), '?'));
    $types = str_repeat('s', count($selectedBills));
    $params = array_merge([$currentDate, $paymentMethod, $user_id], $selectedBills);

    $query = "UPDATE Billing SET PaymentStatus = 'Paid', created_at = ?, PaymentMethod = ?
              WHERE patient_id = ? AND PaymentStatus = 'Unpaid' AND BillingID IN ($placeholders)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss" . $types, ...$params);
} else {
    $query = "UPDATE Billing SET PaymentStatus = 'Paid', created_at = ?, PaymentMethod = ?
              WHERE patient_id = ? AND PaymentStatus = 'Unpaid'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $currentDate, $paymentMethod, $user_id);
}

if ($stmt->execute()) {
    if ($totalAmount > 0) {
        $message = "You have successfully paid ₱" . number_format($totalAmount, 2) . " via $paymentMethod.";
        $insertStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) VALUES (?, 'Payment Successful', ?, NOW())");
        $insertStmt->bind_param("ss", $user_id, $message);
        $insertStmt->execute();
        $insertStmt->close();
    }

    echo json_encode(['success' => true, 'message' => 'Bills updated successfully.']);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
