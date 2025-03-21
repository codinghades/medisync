<?php
session_start();
include '../config/database.php';

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
    $formattedDate = date("F j, Y", strtotime($row['appointment_date'])); // Month Day, Year
    $formattedTime = date("g:i A", strtotime($row['appointment_time'])); // 12-hour format with AM/PM
    $createdDate = date("F j, Y", strtotime($row['created_at'])); // Created date

    $typeFullName = $appointmentTypes[$row['appointment_type']] ?? ucfirst($row['appointment_type']); // Default to capitalized if not found

    echo "<div class='appointment'>
            <dl>
                <dt>
                    <span class='name'>Appointment for {$typeFullName}</span>
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
