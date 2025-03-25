<?php
    session_start();
    include '../config/database.php';
    date_default_timezone_set("Asia/Manila");

    if (!isset($_SESSION["user_id"])) {
        exit("User not logged in");
    }

    $patient_id = $_SESSION["user_id"];

    // Get the closest upcoming appointment
    $query = "SELECT MIN(CONCAT(appointment_date, ' ', appointment_time)) as next_appointment 
              FROM appointments 
              WHERE patient_id = ? AND CONCAT(appointment_date, ' ', appointment_time) >= NOW()";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $stmt->bind_result($nextAppointment);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($nextAppointment) {
        $formattedDateTime = date("F j, Y / g:i A", strtotime($nextAppointment));
        echo "$formattedDateTime";
    } else {
        echo "None";
    }
?>
