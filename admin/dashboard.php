
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admindash.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php
                include '../includes/adminSidebar.php';
            ?>
        </div>
        <div class="Container">
            <div class="middlebar">
                <h1>Dashboard</h1>
                    <div class="mainContainer1">
                        <div class="box">Patient</div>
                        <div class="box">Available Room</div>
                        <div class="box">Available Doctor</div>
                    </div>
                <h1>Patient Data</h1>
            </div>
            <div class="rightbar">
                <h2>Calendar</h2>
                    <div class="box1">Calendar</div>
                <h2>Appointment</h2>
            </div>
        </div>
    </div>
</body>
</html>