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

$queryBills = "
    SELECT SUM(cp.price) AS TotalUnpaid
    FROM Billing b
    JOIN ConsultationPrices cp ON b.ConsultationTypeID = cp.ID
    WHERE b.PaymentStatus = 'Unpaid' AND b.UserID = ?";
$stmt = $conn->prepare($queryBills);
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$stmt->bind_result($totalBills);
$stmt->fetch();
$stmt->close();

$totalBillsFormatted = $totalBills !== null ? "₱" . number_format($totalBills, 2) : "₱0.00";

// Output text response
echo "Appointment: $appointment\n";
echo "Prescription: $prescription\n";
echo "Bills: " . $totalBillsFormatted;;

$conn->close();
?>
