<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['error' => 'User  not logged in']);
    exit;
}

$user_id = $_SESSION["user_id"];
$response = ['unpaid' => [], 'paid' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['billing_id'])) {
    $billingId = $_POST['billing_id'];

    $updateQuery = "UPDATE Billing SET PaymentStatus = 'Paid' WHERE BillingID = ? AND UserID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("is", $billingId, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    exit;
}


$unpaidQuery = "
    SELECT B.BillingID, B.UserID, B.ConsultationTypeID, B.ConsultationDate, B.PaymentStatus, 
           CP.ConsultationType, CP.Price AS Amount 
    FROM Billing B 
    JOIN ConsultationPrices CP ON B.ConsultationTypeID = CP.ID 
    WHERE B.UserID = ? AND B.PaymentStatus = 'Unpaid'";
$stmt = $conn->prepare($unpaidQuery);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response['unpaid'][] = $row;
}

$paidQuery = "
    SELECT B.BillingID, B.UserID, B.ConsultationTypeID, B.ConsultationDate, B.PaymentStatus, 
           CP.ConsultationType, CP.Price AS Amount 
    FROM Billing B 
    JOIN ConsultationPrices CP ON B.ConsultationTypeID = CP.ID 
    WHERE B.UserID = ? AND B.PaymentStatus = 'Paid'";
$stmt = $conn->prepare($paidQuery);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response['paid'][] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>