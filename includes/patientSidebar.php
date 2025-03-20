<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="../assets/css/patientSidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/sidebar.js"></script>
</head>
<body>
    <div class="mainContainer">
        <div class="panel" id="sideBarPanel">
            <div class="header">
                <span class="logo">
                    <img src="../assets/images/Medisync LogoMark.png" alt="Medisync">
                </span> 
                <span class="logoText">
                    <span class="firstName">First Name</span>
                    <span class="lastName">Last Name</span>
                </span>
            </div>
            <div class="navigation">
                <button type="button" id="dashboardButton" onclick="loadPage('dashboard')">
                    <i class='bx bxs-dashboard'></i>
                    <p>Dashboard</p>
                </button>
                <button type="button" id="appointmentsButton" onclick="loadPage('appointment')">
                    <i class='bx bxs-calendar'></i>
                    <p>Appointments</p>
                </button>
                <button type="button" id="prescriptionsButton" onclick="loadPage('prescriptions')">
                    <i class='bx bxs-capsule'></i>
                    <p>Prescriptions</p>
                </button>
                <button type="button" id="billsButton" onclick="loadPage('bills')">
                    <i class='bx bxs-wallet' ></i>
                    <p>Bills</p>
                </button>
                <button type="button" id="testReportsButton" onclick="loadPage('testReports')">
                    <i class='bx bxs-notepad'></i>
                    <p>Test Reports</p>
                </button>
            </div>
            <div class="logout" id="accountButton">
                <button type="button">
                    <i class='bx bx-log-out' ></i>
                    <p>Log Out</p>
                </button>
            </div>
        </div>
    </div>
</body>
</html>