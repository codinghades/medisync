<?php
session_start();
include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $firstName, $hashedPassword);
        $stmt->fetch(); 

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $firstName;
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "error";
}
?>