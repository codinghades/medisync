<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

if (!isset($_SESSION["user_id"])) {
    exit;
}

$response = ['unpaid' => [], 'paid' => []];

// Fetch all unpaid bills
$queryUnpaid = "
    SELECT B.BillingID, U.first_name, U.last_name, B.Amount, B.ConsultationDate 
    FROM Billing B 
    JOIN users U ON B.UserID = U.user_id 
    WHERE B.PaymentStatus = 'Unpaid'
    ORDER BY B.ConsultationDate DESC";

$stmt = $conn->prepare($queryUnpaid);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response['unpaid'][] = [
        'billing_id' => $row['BillingID'],
        'name' => $row['first_name'] . ' ' . $row['last_name'],
        'total_amount' => number_format($row['Amount'], 2),
        'date' => $row['ConsultationDate']
    ];
}

$stmt->close();

// Fetch all paid bills
$queryPaid = "
    SELECT B.BillingID, U.first_name, U.last_name, B.Amount, B.ConsultationDate, B.PaymentMethod 
    FROM Billing B 
    JOIN users U ON B.UserID = U.user_id 
    WHERE B.PaymentStatus = 'Paid'
    ORDER BY B.ConsultationDate DESC";

$stmt = $conn->prepare($queryPaid);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response['paid'][] = [
        'billing_id' => $row['BillingID'],
        'name' => $row['first_name'] . ' ' . $row['last_name'],
        'total_amount' => number_format($row['Amount'], 2),
        'date' => $row['ConsultationDate'],
        'payment_method' => $row['PaymentMethod']
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
