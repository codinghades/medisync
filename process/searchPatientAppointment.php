<?php
include '../config/database.php';

$search = isset($_POST['search']) ? trim($_POST['search']) : "";
$filter = isset($_POST['filter']) ? trim($_POST['filter']) : "";

$appointmentTypes = [
    "laboratory" => "Laboratory & Diagnostics",
    "opd" => "General Medicine (OPD)",
    "pedia" => "Pediatrics",
    "obgyn" => "OB-GYN",
    "ent" => "Ear, Nose & Throat (ENT)"
];

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
    WHERE 1 = 1
";

// Apply search filter
$params = [];
$types = "";
if (!empty($search)) {
    $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR CONCAT(u.first_name, ' ', u.last_name) LIKE ?)";
    $searchParam = "%$search%";
    array_push($params, $searchParam, $searchParam, $searchParam);
    $types .= "sss";
}

// Apply sorting filter
switch ($filter) {
    case '1': // Sort by Name ASC
        $query .= " ORDER BY u.first_name ASC, u.last_name ASC";
        break;
    case '2': // Sort by Name DESC
        $query .= " ORDER BY u.first_name DESC, u.last_name DESC";
        break;
    case '3': // Sort by Newest Appointment
        $query .= " ORDER BY a.created_at DESC";
        break;
    case '4': // Sort by Oldest Appointment
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
        $query .= " ORDER BY a.created_at DESC";
        break;
}

// Prepare and bind parameters if necessary
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$response = [];

while ($row = $result->fetch_assoc()) {
    $appointmentType = isset($appointmentTypes[$row['appointment_type']]) ? $appointmentTypes[$row['appointment_type']] : $row['appointment_type'];

    $response[] = [
        'appointment_id' => $row['appointment_id'],
        'patient_name' => $row['patient_name'],
        'appointment_type' => $appointmentType, // Convert to readable format
        'appointment_date' => $row['appointment_date'],
        'appointment_time' => $row['appointment_time'],
        'status' => $row['status'],
        'created_at' => $row['created_at']
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
