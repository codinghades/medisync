<?php
session_start();
require_once "../config/database.php";

$mobile = "";
$email = "";

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
    $query = "SELECT contact_number, email FROM users WHERE user_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $stmt->bind_result($mobile, $email);
        $stmt->fetch();
        $stmt->close();
    }

    echo json_encode([
        "success" => true,
        "firstName" => $_SESSION["user_name"],
        "lastName" => $_SESSION["user_lastname"] ?? "",
        "contactNumber" => $mobile,
        "email" => $email,
        "user_id" => $_SESSION["user_id"]
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
