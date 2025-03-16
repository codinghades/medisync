<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medisync";

$tempConn = new mysqli($servername, $username, $password);

if ($tempConn->connect_error) {
    die("Connection failed: " . $tempConn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($tempConn->query($sql) === TRUE) {
    echo "Database '$database' created or already exists.<br>";
} else {
    die("Error creating database: " . $tempConn->error . "<br>");
}

$tempConn->close();

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>