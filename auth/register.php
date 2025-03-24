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

    // Generate user_id
    $year = date("Y");
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE user_id LIKE ?");
    $likePattern = "P-$year-%";
    $stmt->bind_param("s", $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row["count"] ?? 0;
    $new_id = sprintf("P-%s-%04d", $year, $count);

    $stmt->close();

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (user_id, first_name, last_name, gender, contact_number, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $new_id, $first_name, $last_name, $gender, $contact_number, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
