<?php
session_start();

if (isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => true,
        "firstName" => $_SESSION["user_name"],
        "lastName" => $_SESSION["user_lastname"] ?? ""
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
