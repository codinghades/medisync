<?php
session_start();
require_once "../config/database.php";

$mobile = "";

if (isset($_SESSION["user_id"])) {
    $userId = $_SESSION["user_id"];
    $query = "SELECT contact_number FROM users WHERE user_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $stmt->bind_result($mobile);
        $stmt->fetch();
        $stmt->close();
    }

    echo json_encode([
        "success" => true,
        "firstName" => $_SESSION["user_name"],
        "lastName" => $_SESSION["user_lastname"] ?? "",
        "contactNumber" => $mobile
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>