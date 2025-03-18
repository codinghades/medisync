<?php include '../includes/patientSidebar.php'?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "schema";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $contact = $_POST['contact'];
    $time = $_POST['time'];
    $appointment_type = $_POST['appointment_type'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO appointments (name, date, contact, time, appointment_type, reason) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $date, $contact, $time, $appointment_type, $reason);

    if ($stmt->execute()){
        echo '<p class="submitText">Appointment submitted successfully</p>';
    }else{
        echo "Error: ". $stmt->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/appointment.css">
    <title>Appointment</title>
</head>
<body>
    <div class="mainAppointment">
        <h1>Appointment</h1>
        <form class="appointment" action="appointment.php" method="POST">
            <div class="top">
                <div class="firstRow">
                    <div class="left">
                        <label>Name:</label>
                        <input class="input" type="text" name="name" required>
                    </div>

                    <div class="right">
                        <label>Date:</label>
                        <input class="input" type="date" name="date" required> 
                    </div>
                </div>

                <div class="secondRow">
                    <div class="left">
                        <label>Contact No:</label>
                        <input class="input" type="text" name="contact" required>
                    </div>

                    <div class="right">
                        <label>Time:</label>
                        <input class="input" type="time" name="time" required>
                    </div>
                </div>
                <div class="thirdRow">
                    <label>Appointment Type:</label>
                    <input class="input" type="text" name="appointment_type" required>
                </div>
            </div>

            <div class="bottom">
                <label>Reason for visits</label>
                <textarea class="input inputReason" name="reason" id="reasons" required></textarea>
            </div>
                <button type="submit" class="btnSubmit" type="button">Submit</button>
        </form>
    </div>
</body>
</html>