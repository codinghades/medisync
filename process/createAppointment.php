<?php
session_start(); // Ensure session is started
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    if (!isset($_SESSION["user_id"])) {
        echo "User not logged in";
        exit;
    }

    $patient_id = $_SESSION["user_id"];
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $contactNumber = trim($_POST['contactNumber']);
    $type = trim($_POST['type']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $details = trim($_POST['details']);

    // Insert appointment into the database without combining date and time
    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, appointment_type, appointment_date, appointment_time, contact_number, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $patient_id, $type, $date, $time, $contactNumber, $details);

    if ($stmt->execute()) {
        echo "Appointment booked successfully";
    } else {
        echo "Failed to book appointment";
    }

    $stmt->close();
    $conn->close();
}
?>
