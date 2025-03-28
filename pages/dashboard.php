<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="../assets/css/patientDashboard.css">
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/fetchNotification.js"></script>
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/patientSidebar.php'?>
        </div>
        <div class="mainContent">
            <div class="titleContainer">
                <div class="title">Dashboard</div>
                <div class="subTitle">Welcome User</div>
            </div>
            <div class="summary">
                <span class="card" id="appointmentCard">
                    <div class="cardIcon"><i class='bx bxs-calendar'></i></div>
                    <div class="cardTitle">Pending Appointment</div>
                    <div class="cardInfo">None</div>
                </span>
                <span class="card" id="prescriptionCard">
                    <div class="cardIcon"><i class='bx bxs-capsule'></i></div>
                    <div class="cardTitle">Prescription</div>
                    <div class="cardInfo">None</div>
                </span>
                <span class="card" id="billingCard">
                    <div class="cardIcon"><i class='bx bxs-wallet' ></i></div>
                    <div class="cardTitle">Bills to Pay</div>
                    <div class="cardInfo">None</div>
                </span>
            </div>
            <div class="notificationsList">
                <div class="header">
                    <p>Notifications</p>
                </div>
                <div class="notification">
                    <dl>
                        <dt>
                            <span class="name">Sample Name</span>
                            <span class="date">March 20, 2025</span>
                        </dt>
                        <dd>
                            <span class="info">This is a sample notification. Thank You.</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</body>
</html>