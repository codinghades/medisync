<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION["user_id"];
$response = ['unpaid' => [], 'paid' => []];

function fetchBillingRecords($conn, $user_id, $status) {
    $query = "
        SELECT B.BillingID, B.UserID, B.ConsultationTypeID, B.ConsultationDate, B.PaymentStatus, 
               CP.ConsultationType, CP.Price AS Amount 
        FROM Billing B 
        JOIN ConsultationPrices CP ON B.ConsultationTypeID = CP.ID 
        WHERE B.UserID = ? AND B.PaymentStatus = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $user_id, $status);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $records = [];
    
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    
    $stmt->close();
    return $records;
}

$response['unpaid'] = fetchBillingRecords($conn, $user_id, 'Unpaid');
$response['paid'] = fetchBillingRecords($conn, $user_id, 'Paid');

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
