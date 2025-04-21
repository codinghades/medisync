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
        COALESCE(b.total_unpaid, 0) AS total_unpaid_bill, 
        COALESCE(a.closest_appointment, 'None') AS closest_appointment
    FROM users u
    LEFT JOIN (
        SELECT patient_id, 'Yes' AS active_prescription
        FROM prescriptions
        WHERE date_prescribed >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY patient_id
    ) p ON u.user_id = p.patient_id
    LEFT JOIN (
        SELECT patient_id, SUM(amount) AS total_unpaid
        FROM Billing
        WHERE PaymentStatus = 'Unpaid'
        GROUP BY patient_id
    ) b ON u.user_id = b.patient_id
    LEFT JOIN (
        SELECT patient_id, MIN(appointment_date) AS closest_appointment
        FROM appointments
        WHERE status = 'Active' AND appointment_date >= CURDATE()
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
        'unpaid_bill' => number_format($row['total_unpaid_bill'], 2),
        'closest_appointment' => ($row['closest_appointment'] !== 'None') ? date('F j, Y g:i A', strtotime($row['closest_appointment'])) : 'None'
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
