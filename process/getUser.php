<?php
session_start();

if (isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => true,
        "firstName" => $_SESSION["user_name"],  // Assuming first name is stored here
        "lastName" => $_SESSION["user_lastname"] ?? "" // Store last name in session if needed
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
