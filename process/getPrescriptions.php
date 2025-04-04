<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$patient_id = $_SESSION["user_id"];
$response = [
    "active" => [],
    "history" => [],
];

// --- Active Prescription ---
$queryActive = "SELECT pd.prescription_id, pd.medicine, pd.dosage, pd.duration, pd.instruction, pd.advice, 
                       p.date_prescribed, u.first_name AS patient_first_name, u.last_name AS patient_last_name, 
                       d.name AS doctor_name, d.mobile AS doctor_mobile, d.doctor_id
                FROM prescription_details pd
                JOIN prescriptions p ON pd.prescription_id = p.id
                JOIN users u ON p.patient_id = u.user_id
                JOIN doctors d ON p.doctor_id = d.id
                WHERE p.patient_id = ? AND p.date_prescribed >= NOW() - INTERVAL 7 DAY
                ORDER BY p.date_prescribed DESC";

$stmt = $conn->prepare($queryActive);
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response["active"][] = $row;
}
$stmt->close();

// --- Prescription History ---
$queryHistory = "SELECT p.id AS prescription_id, p.date_prescribed, d.name AS doctor_name
                 FROM prescriptions p
                 JOIN doctors d ON p.doctor_id = d.id
                 WHERE p.patient_id = ?
                 ORDER BY p.date_prescribed DESC";

$stmt = $conn->prepare($queryHistory);
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $prescriptionId = $row["prescription_id"];

    $queryMedicines = "SELECT medicine FROM prescription_details WHERE prescription_id = ?";
    $stmtMed = $conn->prepare($queryMedicines);
    $stmtMed->bind_param("i", $prescriptionId);
    $stmtMed->execute();
    $resultMed = $stmtMed->get_result();

    $medicines = [];
    while ($med = $resultMed->fetch_assoc()) {
        $medicines[] = $med["medicine"];
    }
    $stmtMed->close();

    $row["medicines"] = $medicines;
    $response["history"][] = $row;
}
$stmt->close();
$conn->close();

echo json_encode($response);
