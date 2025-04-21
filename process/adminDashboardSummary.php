<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

$appointmentTypes = [
    "laboratory" => "Laboratory & Diagnostics",
    "opd"        => "General Medicine (OPD)",
    "pedia"      => "Pediatrics",
    "obgyn"      => "OB-GYN",
    "ent"        => "Ear, Nose & Throat (ENT)"
];

$upcomingAppointmentsQuery = "
    SELECT a.appointment_type, 
        a.appointment_date, 
        a.appointment_time, 
        a.created_at, 
        a.status,
        CONCAT(u.first_name, ' ', u.last_name) AS patient_name
    FROM appointments a
    JOIN users u ON a.patient_id = u.user_id
    WHERE a.status = 'Active'
    ORDER BY a.appointment_date ASC, a.appointment_time ASC";
    
$upcomingAppointmentsResult = $conn->query($upcomingAppointmentsQuery);
$upcomingAppointments = $upcomingAppointmentsResult->fetch_all(MYSQLI_ASSOC);

foreach ($upcomingAppointments as &$row) {
    $row['appointment_type'] = $appointmentTypes[$row['appointment_type']] ?? "Unknown";
    $row['appointment_time'] = date("g:i A", strtotime($row['appointment_time']));
}
unset($row);

// Fetch total active appointments
$totalActiveAppointmentsQuery = "SELECT COUNT(*) AS total FROM appointments WHERE status = 'Active'";
$totalActiveAppointmentsResult = $conn->query($totalActiveAppointmentsQuery);
$totalActiveAppointments = $totalActiveAppointmentsResult->fetch_assoc()['total'];

// Fetch total number of all appointments
$totalAppointmentsQuery = "SELECT COUNT(*) AS total FROM appointments";
$totalAppointmentsResult = $conn->query($totalAppointmentsQuery);
$totalAppointments = $totalAppointmentsResult->fetch_assoc()['total'];

// Fetch total number of active patients
$totalActivePatientsQuery = "
    SELECT COUNT(DISTINCT user_id) AS total
    FROM users 
    WHERE user_id IN (
        SELECT patient_id FROM appointments WHERE status = 'Active'
        
        UNION
        
        SELECT patient_id FROM billing WHERE paymentStatus = 'Unpaid'
        
        UNION
        
        SELECT patient_id FROM prescriptions WHERE date_prescribed >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    )";
$totalActivePatientsResult = $conn->query($totalActivePatientsQuery);
$totalActivePatients = $totalActivePatientsResult->fetch_assoc()['total'];


// Fetch total number of all patients
$totalPatientsQuery = "SELECT COUNT(*) AS total FROM users WHERE role = 'patient'";
$totalPatientsResult = $conn->query($totalPatientsQuery);
$totalPatients = $totalPatientsResult->fetch_assoc()['total'];

// Fetch total unpaid bills count
$totalUnpaidBillsQuery = "SELECT COUNT(*) AS total FROM billing WHERE PaymentStatus = 'Unpaid'";
$totalUnpaidBillsResult = $conn->query($totalUnpaidBillsQuery);
$totalUnpaidBills = $totalUnpaidBillsResult->fetch_assoc()['total'];

// Fetch total amount of unpaid bills
$totalUnpaidAmountQuery = "SELECT SUM(amount) AS total FROM billing WHERE PaymentStatus = 'Unpaid'";
$totalUnpaidAmountResult = $conn->query($totalUnpaidAmountQuery);
$totalUnpaidAmount = $totalUnpaidAmountResult->fetch_assoc()['total'] ?? 0;

// Fetch list of active patients
$activePatientsQuery = "
    SELECT u.user_id, 
       CONCAT(u.first_name, ' ', u.last_name) AS patient_name, 
       u.created_at,
       CASE 
           WHEN EXISTS (
               SELECT 1 FROM prescriptions p 
               WHERE p.patient_id = u.user_id 
               AND p.date_prescribed >= DATE_SUB(NOW(), INTERVAL 7 DAY)
           ) THEN 'Active' 
           ELSE 'None' 
       END AS prescription_status,
       CASE 
           WHEN EXISTS (
               SELECT 1 FROM billing b 
               WHERE b.patient_id = u.user_id 
               AND b.paymentStatus = 'Unpaid'
           ) THEN 'Unpaid' 
           ELSE 'None' 
       END AS unpaid_bill,
       CASE 
           WHEN EXISTS (
               SELECT 1 FROM appointments a 
               WHERE a.patient_id = u.user_id 
               AND a.status = 'Active'
           ) THEN 'Active' 
           ELSE 'None' 
       END AS active_appointment
FROM users u
WHERE u.role = 'patient'
AND (
    EXISTS (
        SELECT 1 FROM prescriptions p 
        WHERE p.patient_id = u.user_id 
        AND p.date_prescribed >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    )
    OR EXISTS (
        SELECT 1 FROM billing b 
        WHERE b.patient_id = u.user_id 
        AND b.paymentStatus = 'Unpaid'
    )
    OR EXISTS (
        SELECT 1 FROM appointments a 
        WHERE a.patient_id = u.user_id 
        AND a.status = 'Active'
    )
)";
$activePatientsResult = $conn->query($activePatientsQuery);
$activePatients = $activePatientsResult->fetch_all(MYSQLI_ASSOC);

$conn->close();

// Response
echo json_encode([
    "upcomingAppointments" => $upcomingAppointments,
    "totalActiveAppointments" => $totalActiveAppointments,
    "totalAppointments" => $totalAppointments,
    "totalActivePatients" => $totalActivePatients,
    "totalPatients" => $totalPatients,
    "totalUnpaidBills" => $totalUnpaidBills,
    "totalUnpaidAmount" => number_format($totalUnpaidAmount, 2),
    "activePatients" => $activePatients
]);
?>
