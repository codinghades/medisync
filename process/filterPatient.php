<?php
session_start();
include '../config/database.php';

if (!isset($_POST['filter'])) {
    echo json_encode(['error' => 'No filter selected']);
    exit;
}

$filter = $_POST['filter'];

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
        SELECT patient_id, 'Yes' AS unpaid_bill
        FROM Billing
        WHERE PaymentStatus = 'Unpaid'
        GROUP BY patient_id
    ) b ON u.user_id = b.patient_id
    LEFT JOIN (
        SELECT patient_id, 'Yes' AS has_appointment
        FROM appointments
        WHERE appointment_date >= CURDATE()
        GROUP BY patient_id
    ) a ON u.user_id = a.patient_id
    WHERE u.role = 'patient' ";

switch ($filter) {
    case '1':
        $query .= " ORDER BY u.first_name ASC, u.last_name ASC";
        break;
    case '2':
        $query .= " ORDER BY u.first_name DESC, u.last_name DESC";
        break;
    case '3':
        $query .= " ORDER BY u.created_at DESC";
        break;
    case '4':
        $query .= " ORDER BY u.created_at ASC";
        break;
    case '5':
        $query .= " AND p.patient_id IS NOT NULL";
        break;
    case '6':
        $query .= " AND b.patient_id IS NOT NULL";
        break;
    case '7':
        $query .= " AND a.patient_id IS NOT NULL";
        break;
    default:
        echo json_encode(['error' => 'Invalid filter option']);
        exit;
}

$result = $conn->query($query);
$response = [];

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

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
