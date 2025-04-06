<?php
session_start();
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

    // Determine Consultation Type ID
    $consultationTypeID = null;
    switch ($type) {
        case 'laboratory': $consultationTypeID = 1; break;
        case 'opd': $consultationTypeID = 2; break;
        case 'pedia': $consultationTypeID = 3; break;
        case 'obgyn': $consultationTypeID = 4; break;
        case 'ent': $consultationTypeID = 5; break;
        default:
            echo "Invalid appointment type";
            exit;
    }

    // Fetch Consultation Price
    $stmtPrice = $conn->prepare("SELECT price FROM consultationprices WHERE id = ?");
    $stmtPrice->bind_param("i", $consultationTypeID);
    $stmtPrice->execute();
    $stmtPrice->bind_result($consultationPrice);
    $stmtPrice->fetch();
    $stmtPrice->close();

    if ($consultationPrice === null) {
        echo "Failed to retrieve consultation price";
        exit;
    }

    $currentDate = date("Y-m-d");

    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, appointment_type, appointment_date, appointment_time, contact_number, notes, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $status = (strtotime("$date $time") >= time()) ? "Active" : "Expired";
    $stmt->bind_param("sssssss", $patient_id, $type, $date, $time, $contactNumber, $details, $status);

    if ($stmt->execute()) {
        $appointmentId = $conn->insert_id; // Get the ID of the newly inserted appointment

        // Corrected INSERT query to include appointment_id
        $stmtBilling = $conn->prepare("INSERT INTO Billing (patient_id, appointment_id, ConsultationTypeID, Amount, PaymentStatus, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $paymentStatus = 'Unpaid';
        // Corrected bind_param to use 's' for patient_id (VARCHAR)
        $stmtBilling->bind_param("siidss", $patient_id, $appointmentId, $consultationTypeID, $consultationPrice, $paymentStatus, $currentDate);

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