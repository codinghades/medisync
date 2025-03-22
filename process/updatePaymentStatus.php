<?php session_start(); include '../config/database.php'; // Check if the user is logged in 
if (!isset($_SESSION["user_id"])) { 
    echo json_encode(['error' => 'User not logged in']); 
    exit; 
} 
$user_id = $_SESSION["user_id"]; // Update all unpaid bills to 'Paid' 
$updateQuery = "UPDATE Billing SET PaymentStatus = 'Paid' WHERE UserID = ? AND PaymentStatus = 'Unpaid'"; 
$stmt = $conn->prepare($updateQuery); $stmt->bind_param("s", $user_id); 
if ($stmt->execute()) { 
    echo json_encode(['success' => true, 'message' => 'All unpaid bills have been marked as paid.']); 
} else { 
    echo json_encode(['success' => false, 'error' => $stmt->error]); 
} 
$stmt->close(); 
$conn->close(); 
?>
