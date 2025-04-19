<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (!isset($_SESSION["user_id"])) {
        echo "User not logged in";
        exit;
    }

    $patient_id = $_SESSION["user_id"];
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $contactNumber = trim($_POST['contactNumber']);
    $type = trim($_POST['type']);
    $date = trim($_POST['date']); // format: YYYY-MM-DD
    $time = trim($_POST['time']); // format: HH:MM
    $details = trim($_POST['details']);

    // Validate date and time
    $appointmentDateTime = "$date $time";
    if (strtotime($appointmentDateTime) < time()) {
        echo " Selected appointment date and time is in the past.";
        exit;
    }

    // Validate consultation type
    $consultationTypeID = match ($type) {
        'laboratory' => 1,
        'opd'        => 2,
        'pedia'      => 3,
        'obgyn'      => 4,
        'ent'        => 5,
        default      => null
    };

    if (!$consultationTypeID) {
        echo "Invalid appointment type";
        exit;
    }

    // Check for existing active appointment on the same date
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE patient_id = ? AND appointment_date = ? AND status = 'Active'");
    $stmtCheck->bind_param("ss", $patient_id, $date);
    $stmtCheck->execute();
    $stmtCheck->bind_result($existingAppointmentCount);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($existingAppointmentCount > 0) {
        echo "You already have an active appointment on " . date('F j, Y', strtotime($date));
        exit;
    }

    // Fetch consultation price
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

    // Insert into appointments
    $status = "Active";
    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, appointment_type, appointment_date, appointment_time, contact_number, notes, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssss", $patient_id, $type, $date, $time, $contactNumber, $details, $status);

    if ($stmt->execute()) {
        $appointmentId = $conn->insert_id;
        $currentDate = date("Y-m-d");
        $paymentStatus = "Unpaid";

        // Insert into Billing
        $stmtBilling = $conn->prepare("INSERT INTO Billing (patient_id, appointment_id, ConsultationTypeID, Amount, PaymentStatus, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtBilling->bind_param("siidss", $patient_id, $appointmentId, $consultationTypeID, $consultationPrice, $paymentStatus, $currentDate);

        if ($stmtBilling->execute()) {
            echo "Appointment booked successfully";
        } else {
            echo "Appointment booked but failed to create billing record";
        }

        $stmtBilling->close();
    } else {
        echo "Failed to book appointment";
    }

    $stmt->close();
    $conn->close();
}
?>
