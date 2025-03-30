<?php
session_start();
include '../config/database.php';

if (!isset($_POST['filter'])) {
    echo json_encode(['error' => 'No filter selected']);
    exit;
}

$appointmentTypes = [
    "laboratory" => "Laboratory & Diagnostics",
    "opd" => "General Medicine (OPD)",
    "pedia" => "Pediatrics",
    "obgyn" => "OB-GYN",
    "ent" => "Ear, Nose & Throat (ENT)"
];

$filter = $_POST['filter'];

$query = "
    SELECT 
        a.id AS appointment_id,
        CONCAT(u.first_name, ' ', u.last_name) AS patient_name,
        a.appointment_type,
        a.appointment_date,
        TIME_FORMAT(a.appointment_time, '%h:%i %p') AS appointment_time,
        a.status,
        a.created_at
    FROM appointments a
    JOIN users u ON a.patient_id = u.user_id
    WHERE 1 = 1"; // Ensures filters append properly

// Apply filters
switch ($filter) {
    case '1': // Sort by Name ASC
        $query .= " ORDER BY u.first_name ASC, u.last_name ASC";
        break;
    case '2': // Sort by Name DESC
        $query .= " ORDER BY u.first_name DESC, u.last_name DESC";
        break;
    case '3': // Sort by Newest Appointment (by created_at)
        $query .= " ORDER BY a.created_at DESC";
        break;
    case '4': // Sort by Oldest Appointment (by created_at)
        $query .= " ORDER BY a.created_at ASC";
        break;
    case '5': // Active Appointments
        $query .= " AND a.status = 'Active' ORDER BY a.appointment_date DESC, a.appointment_time ASC";
        break;
    case '6': // Expired Appointments
        $query .= " AND a.status = 'Expired' ORDER BY a.appointment_date DESC, a.appointment_time ASC";
        break;
    case '7': // Completed Appointments
        $query .= " AND a.status = 'Completed' ORDER BY a.appointment_date DESC, a.appointment_time ASC";
        break;
    default:
        echo json_encode(['error' => 'Invalid filter option']);
        exit;
}

$result = $conn->query($query);
$response = [];

while ($row = $result->fetch_assoc()) {
    $formattedType = $appointmentTypes[$row['appointment_type']] ?? "Unknown";
    $formattedDate = date("F j, Y", strtotime($row['appointment_date']));
    $createdAt = date("F j, Y", strtotime($row['created_at']));

    $response[] = [
        'appointment_id' => $row['appointment_id'],
        'patient_name' => $row['patient_name'],
        'appointment_type' => $formattedType,
        'appointment_date' => $formattedDate,
        'appointment_time' => $row['appointment_time'],
        'status' => $row['status'],
        'created_at' => $createdAt
    ];
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
