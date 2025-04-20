<?php
session_start();
require_once "../config/database.php";

$mobile = "";
$email = "";
$firstName = "";
$lastName = "";

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
    
    // Query to fetch first name, last name, email, and contact number from the database
    $query = "SELECT first_name, last_name, contact_number, email FROM users WHERE user_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $stmt->bind_result($firstName, $lastName, $mobile, $email);
        $stmt->fetch();
        $stmt->close();
    }

    // Return the updated data
    echo json_encode([
        "success" => true,
        "firstName" => $firstName,
        "lastName" => $lastName,
        "contactNumber" => $mobile,
        "email" => $email,
        "user_id" => $userId
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
