<?php
session_start();
include '../config/database.php';
date_default_timezone_set("Asia/Manila");

$currentDateTime = date('Y-m-d H:i:s');

// Update expired appointments
$sql = "UPDATE appointments 
        SET status = 'Expired' 
        WHERE CONCAT(appointment_date, ' ', appointment_time) < ? 
        AND status NOT IN ('Completed', 'Expired')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDateTime);
$stmt->execute();

$stmt->close();
$conn->close();