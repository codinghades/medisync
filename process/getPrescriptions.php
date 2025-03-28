<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

if (!isset($_SESSION["user_id"])) {
    echo "User not logged in";
    exit;
}

$patient_id = $_SESSION["user_id"];

$queryActive = "SELECT pd.prescription_id, pd.medicine, pd.dosage, pd.duration, pd.instruction, pd.advice, 
                        p.date_prescribed, u.first_name AS patient_first_name, u.last_name AS patient_last_name, 
                        d.name AS doctor_name, d.mobile AS doctor_mobile, d.doctor_id
                 FROM prescription_details pd
                 JOIN prescriptions p ON pd.prescription_id = p.id
                 JOIN users u ON p.patient_id = u.user_id
                 JOIN doctors d ON p.doctor_id = d.id
                 WHERE p.patient_id = ? AND p.date_prescribed >= NOW() - INTERVAL 7 DAY
                 ORDER BY p.date_prescribed DESC";

$stmtActive = $conn->prepare($queryActive);
$stmtActive->bind_param("s", $patient_id);
$stmtActive->execute();
$resultActive = $stmtActive->get_result();

if ($row = $resultActive->fetch_assoc()) {
    $prescriptionDate = (new DateTime($row['date_prescribed']))->format("F j, Y");

    echo "<div class='activePrescription'>
            <p class='title'>Active Prescription</p>
            <div class='prescriptionInformation'>
                <div class='header'>
                    <div class='left'>
                        <div class='doctorName'><p>{$row['doctor_name']}</p></div>
                        <div class='doctorIDContainer'><p>Doctor ID:</p><p class='doctorID'>{$row['doctor_id']}</p></div>
                        <div class='doctorNumberContainer'><p>Mobile:</p><p class='doctorNumber'>{$row['doctor_mobile']}</p></div>
                    </div>
                    <div class='right'><img src='../assets/images/Medisync Logo.png' alt='Medisync Logo'></div>
                </div>
                <div class='date'><p>{$prescriptionDate}</p></div>
                <div class='main'>
                    <div class='patientInformation'>
                        <div class='info'><p class='label'>Patient Name:</p><p class='patientName'>{$row['patient_first_name']} {$row['patient_last_name']}</p></div>
                    </div>
                    <div class='medication'>
                        <table>
                            <tr><th>Medicine</th><th>Dosage</th><th>Duration</th><th>Instruction</th></tr>";

    do {
        echo "<tr>
                <td>{$row['medicine']}</td>
                <td>{$row['dosage']}</td>
                <td>{$row['duration']}</td>
                <td>{$row['instruction']}</td>
              </tr>";
    } while ($row = $resultActive->fetch_assoc());

    echo "</table><hr><div class='advice'><p class='label'>Advice Given:</p>";

    $resultActive->data_seek(0);
    while ($rowAdvice = $resultActive->fetch_assoc()) {
        echo "<p class='adviceText'>{$rowAdvice['advice']}</p>";
    }

    echo "</div></div></div><div class='footer'><p>-</p></div></div></div>";

    echo "<div class='downloadPrescriptionButton'>
            <button id='downloadPrescriptionBtn'>Download Prescription (PDF)</button>
          </div>";
} else {
    echo "<div class='activePrescription'><p class='title'>Active Prescription</p><p>No active prescriptions in the last 7 days.</p></div>";
}
$stmtActive->close();

$queryHistory = "SELECT p.id AS prescription_id, p.date_prescribed, d.name AS doctor_name
                 FROM prescriptions p
                 JOIN doctors d ON p.doctor_id = d.id
                 WHERE p.patient_id = ?
                 ORDER BY p.date_prescribed DESC";

$stmtHistory = $conn->prepare($queryHistory);
$stmtHistory->bind_param("s", $patient_id);
$stmtHistory->execute();
$resultHistory = $stmtHistory->get_result();

if ($resultHistory->num_rows > 0) {
    echo "<div class='prescriptionList'>
            <p class='title'>Prescription History</p>";
    while ($rowHistory = $resultHistory->fetch_assoc()) {
        $prescriptionDateHistory = (new DateTime($rowHistory['date_prescribed']))->format("F j, Y");

        $queryMedicines = "SELECT medicine FROM prescription_details WHERE prescription_id = ?";
        $stmtMedicines = $conn->prepare($queryMedicines);
        $stmtMedicines->bind_param("i", $rowHistory['prescription_id']);
        $stmtMedicines->execute();
        $resultMedicines = $stmtMedicines->get_result();

        $medicinesList = [];
        while ($medicineRow = $resultMedicines->fetch_assoc()) {
            $medicinesList[] = $medicineRow['medicine'];
        }
        $stmtMedicines->close();

        $medicinesText = !empty($medicinesList) ? implode(", ", $medicinesList) : "No medicines listed.";

        echo "<div class='prescriptionHistory'>
                <dl>
                    <dt>
                        <span class='name'>Prescription given by {$rowHistory['doctor_name']}</span>
                        <span class='date'>{$prescriptionDateHistory}</span>
                    </dt>
                    <dd>
                        <span class='info'>Based on your consultation, you were prescribed the following medicines: {$medicinesText}.</span>
                    </dd>
                </dl>
              </div>";
    }
    echo "</div>";
} else {
    echo "  
        <div class='prescriptionList'>
            <p class='title'>Prescription History</p>
            <div class='prescriptionHistory'>
                <p class='nothing'>Not recent prescription</p>
            </div>
        </div>";
}
$stmtHistory->close();
$conn->close();

