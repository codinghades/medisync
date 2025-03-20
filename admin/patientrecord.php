<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Record</title>
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
            <table>
                <tr>
                    <th>No.</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Patient</td>
                    <td>Patient</td>
                    <td>Male</td>
                    <td>05/12/1985</td>
                    <td>Active</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>