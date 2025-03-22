<?php
session_start(); // Ensure session is started
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    if (!isset($_SESSION["user_id"])) {
        echo "User  not logged in";
        exit;
    }

    $patient_id = $_SESSION["user_id"];
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $contactNumber = trim($_POST['contactNumber']);
    $type = trim($_POST['type']); // This will be the appointment type as a string
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $details = trim($_POST['details']);

    // Map appointment type to ConsultationTypeID
    $consultationTypeID = null;
    switch ($type) {
        case 'laboratory':
            $consultationTypeID = 1; // Laboratory & Diagnostics
            break;
        case 'opd':
            $consultationTypeID = 2; // General Medicine (OPD)
            break;
        case 'pedia':
            $consultationTypeID = 3; // Pediatrics
            break;
        case 'obgyn':
            $consultationTypeID = 4; // OB-GYN
            break;
        case 'ent':
            $consultationTypeID = 5; // ENT
            break;
        default:
            echo "Invalid appointment type";
            exit;
    }

    // Insert appointment into the database without combining date and time
    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, appointment_type, appointment_date, appointment_time, contact_number, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $patient_id, $type, $date, $time, $contactNumber, $details);

    if ($stmt->execute()) {
        // Now insert into the Billing table
        $stmtBilling = $conn->prepare("INSERT INTO Billing (UserID, ConsultationTypeID, ConsultationDate, PaymentStatus) VALUES (?, ?, ?, ?)");
        $paymentStatus = 'Unpaid'; // Set default payment status
        $stmtBilling->bind_param("ssss", $patient_id, $consultationTypeID, $date, $paymentStatus);

        if ($stmtBilling->execute()) {
            echo "Appointment booked and billing record created successfully";
        } else {
            echo "Failed to create billing record";
        }

        $stmtBilling->close();
    } else {
        echo "Failed to book appointment";
    }

    $stmt->close();
    $conn->close();
}
?>