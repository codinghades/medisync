<?php
include 'config/databaseSetup.php';

function executeSQLFile($conn, $filename) {
    if (!file_exists($filename)) {
        echo "File not found: $filename <br>";
        return;
    }

    $sql = file_get_contents($filename);
    if ($conn->multi_query($sql)) {
        do {} while ($conn->next_result());
        echo "$filename executed successfully.<br>";
    } else {
        echo "Error executing $filename: " . $conn->error . "<br>";
    }
}
executeSQLFile($conn, 'database/schema.sql');

$conn->close();
?>