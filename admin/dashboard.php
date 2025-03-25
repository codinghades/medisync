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
            <?php include '../includes/adminSidebar.php'; ?>
        </div>
        <div class="mainContent">
            <div class="content">
                <h1>Dashboard</h1><br>
                <div class="summary">
                    <span class="card">
                        <div class="cardIcon"><i class='bx bxs-user-check'></i></div>
                        <div class="cardTitle">Patient</div>
                        <div class="cardInfo">None</div>
                    </span>
                    <span class="card">
                        <div class="cardIcon"><i class='bx bxs-bed'></i></div>
                        <div class="cardTitle">Available Room</div>
                        <div class="cardInfo">None</div>
                    </span>
                    <span class="card">
                        <div class="cardIcon"><i class='bx bxs-face-mask' ></i></div>
                        <div class="cardTitle">Available Doctors</div>
                        <div class="cardInfo">None</div>
                    </span>
                </div>
                <div class="notificationsList">
                    <h1>Patient Data</h1>
                    <div class="notification">
                        <dl>
                            <dt>
                                <span class="name">Patient ID: 001</span>
                            </dt>
                            <dd>
                                <span class="info">Patient Information, concern, and consultation.</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="content2">
                <h1>Calendar</h1>
                
                
                <br><br><h1>Appointment</h1><br>
                <div class="appointmentList">
                        <dl>
                            <dt>
                                <span class="id">Patient ID: 001</span>
                                <span class="date">Date of Test: March 20, 2025</span>
                            </dt><br>
                            <dd>
                                <span class="info">Patient Information, concern, and consultation.</span>
                            </dd>
                        </dl>
                    </div>
            </div>
        </div>
    </div>
</body>
</html>