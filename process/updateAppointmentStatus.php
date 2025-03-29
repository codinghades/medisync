<?php
session_start();
include '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['appointment_ids']) || empty($data['appointment_ids'])) {
    echo json_encode(["message" => "No appointments selected"]);
    exit;
}

$ids = implode(",", array_map('intval', $data['appointment_ids']));

$query = "UPDATE appointments SET status = 'Completed' WHERE patient_id IN ($ids)";
$conn->query($query);

$conn->close();
echo json_encode(["message" => "Appointments marked as completed"]);
