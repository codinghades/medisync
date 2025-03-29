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

$currentDate = date("Y-m-d"); // Get the current date

$updateQuery = "UPDATE Billing SET PaymentStatus = 'Paid', ConsultationDate = ?, PaymentMethod = ? WHERE UserID = ? AND PaymentStatus = 'Unpaid'";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("sss", $currentDate, $paymentMethod, $user_id);

if ($stmt->execute()) {
    if ($totalAmount > 0) {
        $message = "You have successfully paid ₱" . number_format($totalAmount, 2) . " via $paymentMethod.";
        $insertStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) VALUES (?, 'Payment Successful', ?, NOW())");
        $insertStmt->bind_param("ss", $user_id, $message);
        $insertStmt->execute();
        $insertStmt->close();
    }

    echo json_encode(['success' => true, 'message' => 'All unpaid bills have been marked as paid.']);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}


$stmt->close();
$conn->close();
?>
