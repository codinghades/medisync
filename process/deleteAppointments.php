<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['appointment_ids']) || empty($data['appointment_ids'])) {
    echo json_encode(["message" => "No appointments selected"]);
    exit;
}

$appointmentTypes = [
    "laboratory" => "Laboratory & Diagnostics",
    "opd"         => "General Medicine (OPD)",
    "pedia"       => "Pediatrics",
    "obgyn"       => "OB-GYN",
    "ent"         => "Ear, Nose & Throat (ENT)"
];

$appointmentIdsToDelete = array_map('intval', $data['appointment_ids']);
$idsToDeleteString = implode(",", $appointmentIdsToDelete);

// Fetch details of the appointments being cancelled for notification
$querySelectCancelled = "SELECT id, patient_id, appointment_type, appointment_date, appointment_time
                         FROM appointments
                         WHERE id IN ($idsToDeleteString)";
$resultSelectCancelled = $conn->query($querySelectCancelled);

$cancelledAppointments = [];
if ($resultSelectCancelled->num_rows > 0) {
    while ($row = $resultSelectCancelled->fetch_assoc()) {
        $cancelledAppointments[] = $row;
    }
}

$deletedBillCount = 0;

// Function to delete billing record by appointment ID
function deleteBillingByAppointmentId($conn, $appointmentId) {
    global $deletedBillCount;
    $queryDeleteBill = "DELETE FROM billing WHERE appointment_id = ?";
    $stmtDeleteBill = $conn->prepare($queryDeleteBill);
    $stmtDeleteBill->bind_param("i", $appointmentId);
    if ($stmtDeleteBill->execute()) {
        $deletedBillCount++;
        $stmtDeleteBill->close();
        return true;
    } else {
        error_log("Error deleting bill for appointment ID " . $appointmentId . ": " . $stmtDeleteBill->error);
        $stmtDeleteBill->close();
        return false;
    }
}

// Delete the appointments
$queryDelete = "DELETE FROM appointments WHERE id IN ($idsToDeleteString)";
if ($conn->query($queryDelete)) {
    // Send notifications for each cancelled appointment and delete billing
    foreach ($cancelledAppointments as $appointment) {
        $patientId = $appointment['patient_id'];
        $appointmentTypeKey = $appointment['appointment_type'];
        $niceAppointmentType = isset($appointmentTypes[$appointmentTypeKey]) ? $appointmentTypes[$appointmentTypeKey] : ucfirst($appointmentTypeKey);
        $formattedDate = date("F j, Y", strtotime($appointment["appointment_date"]));
        $formattedTime = date("g:i A", strtotime($appointment["appointment_time"]));
        $message = "Your appointment for " . htmlspecialchars($niceAppointmentType) . " on $formattedDate at $formattedTime has been cancelled.";
        $title = "Appointment Cancelled";

        $insertStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, created_at) VALUES (?, ?, ?, NOW())");
        $insertStmt->bind_param("sss", $patientId, $title, $message);
        $insertStmt->execute();
        $insertStmt->close();

        // Delete corresponding bill using the new function
        deleteBillingByAppointmentId($conn, $appointment['id']);
    }
    echo json_encode(["message" => "Appointments deleted successfully, " . $deletedBillCount . $appiontmentID . " corresponding bills deleted, and notifications sent"]);
} else {
    echo json_encode(["error" => "Error deleting appointments: " . $conn->error]);
}

$conn->close();
?>