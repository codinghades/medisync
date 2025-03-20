<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription</title>
    <link rel="stylesheet" href="../assets/css/prescription.css">
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
                    <span class="lastName">Last Name</span>
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

                <button type="button" id="prescriptionsButton">
                    <i class='bx bxs-capsule'></i>
                    <p>Prescriptions</p>
                </button>

                <button type="button" id="billsButton">
                    <i class='bx bxs-wallet'></i>
                    <p>Bills</p>
                </button>

                <button type="button" id="testReportsButton">
                    <i class='bx bxs-notepad'></i>
                    <p>Test Reports</p>
                </button>
            </div>
            <div class="logout" id="accountButton">
                <button type="button">
                    <i class='bx bx-log-out'></i>
                    <p>Log Out</p>
                </button>
            </div>
        </div>
        <div class="letter-box">
            <div class="letter-nav">
                <h2>Morong General Hospital</h2>
                <p>Morong, #1253 San Juan St.</p>
                
            </div>

            <div class="letter-body">
                    <div class="patient-info">
                        <div class="info-left">
                            <p style="color: #233dff;">Doctor Name: </p>
                            <p>Position: </p>
                            <p>Number: </p>
                        </div>

                        <div class="info-right">
                            <p>Prescription Number: </p>
                            <p>Date: </p>
                            
                        </div>
                    </div>

                    <div class="Medicines">
                        <p>Medicine_1</p>
                        <p>Medicine_2</p>
                        <p>Medicine_3</p>
                    </div>

                    <div class="patient-info-bottom">

                    <div class="left-box">

                    </div>

                        <div class="right-box">
                            <p>Name: </p>
                            <p>Age: </p>
                            <p>Address: </p>
                            <p>Contact Number: </p>
                        </div>
                    </div>

            </div>
        </div>
    </div>

</body>

</html>