<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="../assets/css/adminSidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="../assets/js/sidebar.js"></script>
    
</head>
<body>
    <div class="mainContainer">
        <div class="panel" id="sideBarPanel">
            <div class="header">
                <div class="logo">
                    <img src="../assets/images/Medisync LogoMark.png" alt="Medisync">
                </div> 
                <div class="logoText">
                    <div class="firstName">First Name</div>
                    <div class="lastName">Last Name</div>
                    <div class="role">Admin</div>
                </div>
            </div>
            <div class="navigation">
                <button type="button" id="dashboardButton" onclick="loadPage('dashboard')">
                    <i class='bx bxs-dashboard'></i>
                    <p>Dashboard</p>
                </button>
                <button type="button" id="appointmentsButton" onclick="loadPage('Appointments')">
                    <i class='bx bxs-calendar'></i>
                    <p>Appointments</p>
                </button>
                <button type="button" id="patientsButton" onclick="loadPage('Patients')">
                    <i class='bx bxs-user-detail'></i>
                    <p>Patients</p>
                </button>
                <button type="button" id="paymentsButton" onclick="loadPage('Payments')">
                    <i class='bx bxs-wallet' ></i>
                    <p>Payments</p>
                </button>
            </div>
            <div class="accounts" id="logoutButton">
                <button type="button">
                    <i class='bx bx-log-out' ></i>
                    <p>Log Out</p>
                </button>
            </div>
        </div>
    </div>
</body>
</html>