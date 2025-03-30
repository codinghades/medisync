<?php
include '../config/database.php';

$search = isset($_POST['search']) ? trim($_POST['search']) : "";
$filter = isset($_POST['filter']) ? trim($_POST['filter']) : "";

// Query to fetch patient list
$query = "
    SELECT 
        u.user_id, 
        CONCAT(u.first_name, ' ', u.last_name) AS name, 
        u.created_at AS registration_date, 
        COALESCE(p.active_prescription, 'No') AS active_prescription, 
        COALESCE(b.total_unpaid, 0) AS total_unpaid_bill, 
        COALESCE(a.closest_appointment, 'None') AS closest_appointment,
        COALESCE(a.appointment_time, NULL) AS appointment_time
    FROM users u
    LEFT JOIN (
        SELECT patient_id, 'Yes' AS active_prescription
        FROM prescriptions
        WHERE date_prescribed >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY patient_id
    ) p ON u.user_id = p.patient_id
    LEFT JOIN (
        SELECT patient_id, SUM(amount) AS total_unpaid
        FROM Billing
        WHERE PaymentStatus = 'Unpaid'
        GROUP BY patient_id
    ) b ON u.user_id = b.patient_id
    LEFT JOIN (
        SELECT patient_id, MIN(appointment_date) AS closest_appointment, appointment_time
        FROM appointments
        WHERE status = 'Active' AND appointment_date >= CURDATE()
        GROUP BY patient_id
    ) a ON u.user_id = a.patient_id
    WHERE u.role = 'patient'
";

// Apply search filter
$params = [];
$types = "";
if (!empty($search)) {
    $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR CONCAT(u.first_name, ' ', u.last_name) LIKE ?)";
    $searchParam = "%$search%";
    array_push($params, $searchParam, $searchParam, $searchParam);
    $types .= "sss";
}

// Apply sorting filter
switch ($filter) {
    case '1': // Sort by Name ASC
        $query .= " ORDER BY u.first_name ASC, u.last_name ASC";
        break;
    case '2': // Sort by Name DESC
        $query .= " ORDER BY u.first_name DESC, u.last_name DESC";
        break;
    case '3': // Sort by Newest Registration
        $query .= " ORDER BY u.created_at DESC";
        break;
    case '4': // Sort by Oldest Registration
        $query .= " ORDER BY u.created_at ASC";
        break;
    case '5': // Patients with Active Prescriptions
        $query .= " AND p.active_prescription = 'Yes' ORDER BY u.first_name ASC, u.last_name ASC";
        break;
    case '6': // Patients with Unpaid Bills (Highest to Lowest)
        $query .= " AND b.total_unpaid > 0 ORDER BY b.total_unpaid DESC, u.first_name ASC, u.last_name ASC";
        break;
    case '7': // Patients with Active Appointments
        $query .= " AND a.closest_appointment IS NOT NULL ORDER BY a.closest_appointment ASC";
        break;
    default:
        $query .= " ORDER BY u.created_at DESC";
        break;
}


// Prepare and bind parameters if necessary
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$response = [];

while ($row = $result->fetch_assoc()) {
    $formattedAppointment = "None";
    if ($row['closest_appointment'] !== "None" && !empty($row['appointment_time'])) {
        $formattedAppointment = date("F j, Y", strtotime($row['closest_appointment'])) . " at " . date("g:i A", strtotime($row['appointment_time']));
    }

    $response[] = [
        'user_id' => $row['user_id'],
        'name' => $row['name'],
        'registration_date' => $row['registration_date'],
        'active_prescription' => $row['active_prescription'],
        'unpaid_bill' => number_format($row['total_unpaid_bill'], 2),
        'closest_appointment' => $formattedAppointment
    ];
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
