<?php
session_start();
include("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT user_id, first_name, last_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $firstName, $lastName, $hashedPassword, $role);
        $stmt->fetch(); 

        if (password_verify($password, $hashedPassword)) {
            // Store user info in session
            $_SESSION["user_id"] = $user_id;
            $_SESSION["user_name"] = $firstName;
            $_SESSION["user_lastname"] = $lastName;
            $_SESSION["role"] = $role;

            echo $role; // Return "admin" or "patient"
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Email not found";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
