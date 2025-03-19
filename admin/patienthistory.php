<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History</title>
    <link rel="stylesheet" href="../assets/css/adminSidebar.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/patients.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                    <span class="role">Admin</span>
                </span>
            </div>
            <div class="navigation">
                <button type="button" id="dashboardButton">
                    <i class='bx bxs-dashboard'></i>
                    <p>Dashboard</p>
                </button>
                <button type="button" id="appointmentsButton">
                    <i class='bx bxs-calendar'></i>
                    <p>Appointments</p>
                </button>
                <button type="button" id="patientsButton">
                    <i class='bx bxs-user-detail'></i>
                    <p>Patients</p>
                </button>
                <button type="button" id="paymentsButton">
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
    <div class="main-content">
        <h1>Patients</h1>
        <div class="tabs">
            <div class="tab">Lists</div>
            <div class="tab">Records</div>
            <div class="tab">History</div>
            <div class="tab">Appointment & Consultation</div>
        </div>
        <div class="container">
            <h2>Medical History</h2>
            <div class="history-card">
                <h3>Patient One</h3>
                <p><strong>Condition:</strong> Hypertension</p>
                <p><strong>Medications:</strong> Lisinopril, Amlodipine</p>
                <p><strong>Last Visit:</strong> 2024-01-15</p>
                <hr>
            </div>
            <div class="history-card">
                <h3>Patient Two</h3>
                <p><strong>Condition:</strong> Diabetes Type 2</p>
                <p><strong>Medications:</strong> Metformin</p>
                <p><strong>Last Visit:</strong> 2024-02-10</p>
                <hr>
            </div>
        </div>
    </div>
    </body>
</html>