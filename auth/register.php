<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['firstName']);
    $last_name = trim($_POST['lastName']);
    $gender = trim($_POST['gender']);
    $contact_number = trim($_POST['contactNumber']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = "patient";

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, gender, contact_number, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $first_name, $last_name, $gender, $contact_number, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
