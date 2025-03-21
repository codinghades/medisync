<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila"); // Set correct timezone

if (!isset($_SESSION["user_id"])) {
    echo "User not logged in";
    exit;
}

$patient_id = $_SESSION["user_id"];

// Mapping short types to full names
$appointmentTypes = [
    "laboratory" => "Laboratory & Diagnostics",
    "opd" => "General Medicine (OPD)",
    "pedia" => "Pediatrics",
    "obgyn" => "OB-GYN",
    "ent" => "Ear, Nose & Throat (ENT)"
];

$stmt = $conn->prepare("SELECT appointment_type, appointment_date, appointment_time, created_at FROM appointments WHERE patient_id = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Format appointment date and time
    $appointmentDateTime = new DateTime("{$row['appointment_date']} {$row['appointment_time']}", new DateTimeZone("Asia/Manila"));
    $formattedDate = $appointmentDateTime->format("F j, Y"); // Month Day, Year
    $formattedTime = $appointmentDateTime->format("g:i A");  // 12-hour format with AM/PM

    // Format created date
    $createdDate = (new DateTime($row['created_at']))->format("F j, Y");

    // Get full type name
    $typeFullName = $appointmentTypes[$row['appointment_type']] ?? ucfirst($row['appointment_type']);

    // Check if appointment is active or expired
    $currentDateTime = new DateTime("now", new DateTimeZone("Asia/Manila"));
    $status = ($appointmentDateTime > $currentDateTime) 
        ? "<span style='color: green;'>(Active)</span>" 
        : "<span style='color: red;'>(Expired)</span>";

    echo "<div class='appointment'>
            <dl>
                <dt>
                    <span class='name'>Appointment for {$typeFullName} {$status}</span>
                    <span class='date'>{$createdDate}</span>
                </dt>
                <dd>
                    <span class='info'>You have booked an appointment for {$typeFullName} on {$formattedDate} at {$formattedTime}. Please arrive at least 30 minutes early to avoid any issues. Thank you.</span>
                </dd>
            </dl>
        </div>";
}

$stmt->close();
$conn->close();
?>
