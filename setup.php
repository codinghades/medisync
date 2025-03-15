<?php
include 'config/database.php';
function executeSQLFile($conn, $filename) {
    $sql = file_get_contents($filename);
    if ($conn->multi_query($sql)) {
        echo "$filename executed successfully.<br>";
    } else {
        echo "Error executing $filename: " . $conn->error . "<br>";
    }
}

executeSQLFile($conn, 'database/schema.sql');
executeSQLFile($conn, 'database/seed.sql');

$conn->close();
?>