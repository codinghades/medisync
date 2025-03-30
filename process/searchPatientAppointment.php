<?php
session_start();
include '../config/database.php';

$appointmentTypes = [
    "laboratory" => "Laboratory & Diagnostics",
    "opd" => "General Medicine (OPD)",
    "pedia" => "Pediatrics",
    "obgyn" => "OB-GYN",
    "ent" => "Ear, Nose & Throat (ENT)"
];

// Get search query
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

$query = "
    SELECT 
        a.id,
        a.patient_id, 
        u.first_name, 
        u.last_name, 
        a.appointment_type, 
        a.appointment_date, 
        a.appointment_time, 
        a.created_at,
        a.status
    FROM appointments a
    JOIN users u ON a.patient_id = u.user_id
    WHERE 
        (u.first_name LIKE ? OR 
         u.last_name LIKE ? OR 
         CONCAT(u.first_name, ' ', u.last_name) LIKE ? OR 
         u.user_id LIKE ?)
    ORDER BY a.appointment_date DESC, a.appointment_time ASC";

$stmt = $conn->prepare($query);
$searchParam = "%$search%";
$stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

$response = [];

while ($row = $result->fetch_assoc()) {
    $formattedType = $appointmentTypes[$row['appointment_type']] ?? "Unknown";
    $formattedDate = date("F j, Y", strtotime($row['appointment_date']));
    $formattedTime = date("h:i A", strtotime($row['appointment_time']));
    $createdAt = date("F j, Y", strtotime($row['created_at']));

    $response[] = [
        'appointment_id' => $row['id'],
        'patient_name' => $row['first_name'] . ' ' . $row['last_name'],
        'appointment_type' => $formattedType,
        'appointment_date' => $formattedDate,
        'appointment_time' => $formattedTime,
        'status' => $row['status'],
        'created_at' => $createdAt
    ];
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
