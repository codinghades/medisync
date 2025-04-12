<?php
session_start();
include '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['appointment_id'])) {
    echo json_encode(["message" => "No appointment selected"]);
    exit;
}

$appointment_id = intval($data['appointment_id']);

$query = "SELECT status FROM appointments WHERE id = $appointment_id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    echo json_encode(["message" => "Appointment not found"]);
    exit;
}

$row = $result->fetch_assoc();
$status = strtolower(trim($row['status']));

if ($status !== "active") {
    echo json_encode(["message" => "Appointment is already $status"]);
    exit;
}

$update = "UPDATE appointments SET status = 'Completed' WHERE id = $appointment_id";
$conn->query($update);

$conn->close();
echo json_encode(["message" => "Appointment marked as completed"]);
