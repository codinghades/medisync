<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/adminDashboard.css">
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php
                include '../includes/adminSidebar.php';
            ?>
        </div>
        <div class="mainContent">
            <div class="titleContainer">
                <p class="title">Dashboard</p>
                <p class="subTitle">Welcome Admin</p>
            </div>
            <div class="upcomingAppointments">
                <div class="header">
                    <p>Upcoming Appointments</p>
                </div>
                <div class="list">
                    <dl>
                        <dt>
                            <span class="name">Appointment by [Name]</span>
                            <span class="date">[Creation Date]</span>
                        </dt>
                        <dd>
                            <span class="info">[Name] have booked an appointment in [Type] on [Date] at [Time].</span>
                        </dd>
                    </dl>
                    <dl>
                        <dt>
                            <span class="name">Appointment by [Name]</span>
                            <span class="date">[Creation Date]</span>
                        </dt>
                        <dd>
                            <span class="info">[Name] have booked an appointment in [Type] on [Date] at [Time].</span>
                        </dd>
                    </dl>
                    <dl>
                        <dt>
                            <span class="name">Appointment by [Name]</span>
                            <span class="date">[Creation Date]</span>
                        </dt>
                        <dd>
                            <span class="info">[Name] have booked an appointment in [Type] on [Date] at [Time].</span>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="summaryCards">
                <div class="header">
                    <p>Summary</p>
                </div>
                <div class="cards">
                    <div class="card" id="totalActiveAppointmetns">
                        <div class="title">
                            <i class='bx bxs-calendar-check'></i>
                            <p>Active Appointments</p>
                        </div>
                        <div class="content">
                            <p class="total">10</p>
                        </div>
                    </div>
                    <div class="card" id="totalActivePatients">
                        <div class="title">
                            <i class='bx bxs-user-check' ></i>
                            <p>Active Patients</p>
                        </div>
                        <div class="content">
                            <p class="total">10</p>
                        </div>
                    </div>
                    <div class="card" id="totalAppointmetns">
                        <div class="title">
                            <i class='bx bxs-calendar' ></i>
                            <p>Total Appointments</p>
                        </div>
                        <div class="content">
                            <p class="total">10</p>
                        </div>
                    </div>
                    <div class="card" id="totalPatients">
                        <div class="title">
                            <i class='bx bxs-user-detail' ></i>
                            <p>Total Patients</p>
                        </div>
                        <div class="content">
                        <p class="total">10</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="unpaidBills">
                <div class="header">
                    <p>Unpaid Bills</p>
                </div>
                <div class="totalUnpaidBills">
                    <div class="numberOfUnpaidBills">
                        <div class="title">Number of Unpaid Bills</div>
                        <p>10</p>
                    </div>
                    <div class="amountOfUnpaidbills">
                        <div class="title">Amount of Unpaid bills</div>
                        <p>1000.00</p>
                    </div>
                </div>
            </div>
            <div class="activePatientList">
                <div class="header">
                    <p>Active Patient List</p>
                </div>
                <div class="list">
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Register Date</th>
                            <th>Prescription Status</th>
                            <th>Unpaid Bill</th>
                            <th>Appointment</th>
                        </tr>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>March 26, 2025</td>
                            <td>Active</td>
                            <td>None</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>March 26, 2025</td>
                            <td>Active</td>
                            <td>None</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>March 26, 2025</td>
                            <td>Active</td>
                            <td>None</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>March 26, 2025</td>
                            <td>Active</td>
                            <td>None</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>March 26, 2025</td>
                            <td>Active</td>
                            <td>None</td>
                            <td>Active</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>