<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

if (!isset($_SESSION["user_id"])) {
    exit;
}

$patient_id = $_SESSION["user_id"];
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime("+1 day"));

$stmt = $conn->prepare("SELECT appointment_type, appointment_date, appointment_time FROM appointments 
                        WHERE patient_id = ? AND appointment_date BETWEEN ? AND ?");
$stmt->bind_param("sss", $patient_id, $today, $tomorrow);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $formattedDate = date("F j, Y", strtotime($row["appointment_date"]));
    $formattedTime = date("g:i A", strtotime($row["appointment_time"]));
    $message = "You have an upcoming appointment on $formattedDate at $formattedTime. Please be on time.";

    $checkStmt = $conn->prepare("SELECT id FROM notifications WHERE user_id = ? AND title = 'Upcoming Appointment' AND message = ?");
    $checkStmt->bind_param("ss", $patient_id, $message);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows == 0) {
        $insertStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) VALUES (?, 'Upcoming Appointment', ?, NOW())");
        $insertStmt->bind_param("ss", $patient_id, $message);
        $insertStmt->execute();
        $insertStmt->close();
    }

    $checkStmt->close();
}

$stmt->close();
$conn->close();

echo json_encode(["status" => "success"]);
?>
