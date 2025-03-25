<?php
session_start();
$response = ["loggedIn" => false];

if (isset($_SESSION["role"])) {
    $response["loggedIn"] = true;
    $response["redirect"] = ($_SESSION["role"] === "patient") ? "../pages/dashboard.php" : "../admin/dashboard.php";
}

echo json_encode($response);
?>
