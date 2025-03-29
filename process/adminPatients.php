<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

$response = [];

$query = "
    SELECT 
        u.user_id, 
        u.first_name, 
        u.last_name, 
        u.created_at, 
        COALESCE(p.active_prescription, 'No') AS active_prescription, 
        COALESCE(b.unpaid_bill, 'No') AS unpaid_bill, 
        COALESCE(a.has_appointment, 'No') AS has_appointment
    FROM users u
    LEFT JOIN (
        SELECT patient_id, 'Yes' AS active_prescription
        FROM prescriptions
        WHERE date_prescribed >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY patient_id
    ) p ON u.user_id = p.patient_id
    LEFT JOIN (
        SELECT UserID, 'Yes' AS unpaid_bill
        FROM Billing
        WHERE PaymentStatus = 'Unpaid'
        GROUP BY UserID
    ) b ON u.user_id = b.UserID
    LEFT JOIN (
        SELECT patient_id, 'Yes' AS has_appointment
        FROM appointments
        WHERE status = 'Active'
        GROUP BY patient_id
    ) a ON u.user_id = a.patient_id
    WHERE u.role = 'patient'
    ORDER BY u.first_name, u.last_name";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response[] = [
        'user_id' => $row['user_id'],
        'name' => $row['first_name'] . ' ' . $row['last_name'],
        'registration_date' => $row['created_at'],
        'active_prescription' => $row['active_prescription'],
        'unpaid_bill' => $row['unpaid_bill'],
        'has_appointment' => $row['has_appointment']
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
