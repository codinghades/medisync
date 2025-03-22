<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

if (!isset($_SESSION["user_id"])) {
    exit("User not logged in");
}

$patient_id = $_SESSION["user_id"];
$appointment = "None";
$prescription = "None";
// $bills = "None";

// Get upcoming appointment
$queryAppointment = "SELECT MIN(CONCAT(appointment_date, ' ', appointment_time)) as next_appointment 
                     FROM appointments WHERE patient_id = ? AND CONCAT(appointment_date, ' ', appointment_time) >= NOW()";
$stmt = $conn->prepare($queryAppointment);
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$stmt->bind_result($nextAppointment);
$stmt->fetch();
$stmt->close();

if ($nextAppointment) {
    $appointment = date("F j, Y / g:i A", strtotime($nextAppointment));
}

// Get active prescription
$queryPrescription = "SELECT COUNT(*) FROM prescriptions WHERE patient_id = ? AND date_prescribed >= NOW() - INTERVAL 7 DAY";
$stmt = $conn->prepare($queryPrescription);
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$stmt->bind_result($activePrescription);
$stmt->fetch();
$stmt->close();

if ($activePrescription > 0) {
    $prescription = "Active";
}

// Get total bills
// $queryBills = "SELECT SUM(amount_due) FROM bills WHERE patient_id = ? AND status = 'unpaid'";
// $stmt = $conn->prepare($queryBills);
// $stmt->bind_param("s", $patient_id);
// $stmt->execute();
// $stmt->bind_result($totalBills);
// $stmt->fetch();
// $stmt->close();

// if ($totalBills > 0) {
//     $bills = "₱" . number_format($totalBills, 2);
// }

// Output text response
echo "Appointment: $appointment\n";
echo "Prescription: $prescription\n";
// echo "Bills: $bills\n";

$conn->close();
?>
