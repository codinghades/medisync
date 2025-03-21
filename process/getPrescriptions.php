<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

if (!isset($_SESSION["user_id"])) {
    echo "User not logged in";
    exit;
}

$patient_id = $_SESSION["user_id"];

// Query to get the latest prescription within the last 7 days, including multiple medicine details
$query = "SELECT pd.prescription_id, pd.medicine, pd.dosage, pd.duration, pd.instruction, pd.advice, 
                 p.date_prescribed, u.first_name AS patient_first_name, u.last_name AS patient_last_name, 
                 d.name AS doctor_name, d.mobile AS doctor_mobile, d.doctor_id
          FROM prescription_details pd
          JOIN prescriptions p ON pd.prescription_id = p.id
          JOIN users u ON p.patient_id = u.user_id
          JOIN doctors d ON p.doctor_id = d.id
          WHERE p.patient_id = ? AND p.date_prescribed >= NOW() - INTERVAL 7 DAY
          ORDER BY p.date_prescribed DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Format the prescription date
    $prescriptionDate = (new DateTime($row['date_prescribed']))->format("F j, Y");

    // Display prescription header
    echo "<div class='prescriptionInformation'>
            <div class='header'>
                <div class='left'>
                    <div class='doctorName'>
                        <p>{$row['doctor_name']}</p>
                    </div>
                    <div class='doctorIDContainer'>
                        <p>Doctor ID:</p>
                        <p class='doctorID'>{$row['doctor_id']}</p>
                    </div>
                    <div class='doctorNumberContainer'>
                        <p>Mobile:</p>
                        <p class='doctorNumber'>{$row['doctor_mobile']}</p>
                    </div>
                </div>
                <div class='right'>
                    <img src='../assets/images/Medisync Logo.png' alt='Medisync Logo'>
                </div>
            </div>
            <div class='date'>
                <p>{$prescriptionDate}</p>
            </div>
            <div class='main'>
                <div class='patientInformation'>
                    <div class='info'>
                        <p class='label'>Patient Name:</p>
                        <p class='patientName'>{$row['patient_first_name']} {$row['patient_last_name']}</p>
                    </div>
                </div>

                <div class='medication'>
                    <table>
                        <tr>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th>Duration</th>
                            <th>Instruction</th>
                        </tr>";

    // Loop through prescription details (medicines)
    do {
        echo "<tr>
                <td>{$row['medicine']}</td>
                <td>{$row['dosage']}</td>
                <td>{$row['duration']}</td>
                <td>{$row['instruction']}</td>
              </tr>";
    } while ($row = $result->fetch_assoc()); // Continue looping through prescription details

    echo "</table>";

    // Reset pointer to the start of the result set to fetch all advices
    $result->data_seek(0);

    echo "<hr>
          <div class='advice'>
            <p class='label'>Advice Given:</p>";

    // Loop again to display all advice for each prescription
    while ($advice_row = $result->fetch_assoc()) {
        $advice = $advice_row['advice'];
        echo "<p class='adviceText'>{$advice}</p>";
    }

    echo "</div>
        </div> <!-- End medication -->
            </div>  
            <div class='footer'><p>-</p></div>
        </div>";
} else {
    echo "<p>No active prescriptions in the last 7 days.</p>";
}

$stmt->close();
$conn->close();
?>
