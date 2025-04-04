<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$patient_id = $_SESSION["user_id"];
$appointmentTypes = [
    "laboratory" => "Laboratory & Diagnostics",
    "opd" => "General Medicine (OPD)",
    "pedia" => "Pediatrics",
    "obgyn" => "OB-GYN",
    "ent" => "Ear, Nose & Throat (ENT)"
];

$stmt = $conn->prepare("SELECT appointment_type, appointment_date, appointment_time, created_at, status FROM appointments WHERE patient_id = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];

while ($row = $result->fetch_assoc()) {
    $appointmentDateTime = new DateTime("{$row['appointment_date']} {$row['appointment_time']}", new DateTimeZone("Asia/Manila"));
    $row['formatted_date'] = $appointmentDateTime->format("F j, Y");
    $row['formatted_time'] = $appointmentDateTime->format("g:i A");
    $row['created_date'] = (new DateTime($row['created_at']))->format("F j, Y");
    $row['type_full'] = $appointmentTypes[$row['appointment_type']] ?? ucfirst($row['appointment_type']);

    $appointments[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(["appointments" => $appointments]);
