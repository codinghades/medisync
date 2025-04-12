<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="../assets/css/adminAppointment.css">
    <script src="../assets/js/adminAppointments.js"></script>
    <script src="../assets/js/adminActiveAppointments.js"></script>
    <script src="../assets/js/searchResultAppointment.js"></script>
    <script src="../assets/js/download.js"></script>
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/adminSidebar.php'; ?>
        </div>
        <div class="mainContent">
            <div class="titleContainer">
                <div class="title">Appointments</div>
                <div class="subTitle">Manage Patient Appointments</div>
            </div>
            <div class="activeAppointments">
                <div class="header">
                    <p>Active Appointments</p>
                </div>
                <div class="list">
                    <form>
                        <table>
                            <tr>
                                <th>Name</th>
                                <th>Service Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                            <tr>
                                <td>Juan Dela Cruz</td>
                                <td>General Medicine (OPD)</td>
                                <td>March 29, 2025</td>
                                <td>9:00 AM</td>
                                <td class="status"><span class="statusText">Active</span></td>
                                <td>March 25, 2025</td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <div class="allAppointments">
                <div class="header">
                    <p>All Appointments</p>
                </div>
                <div class="searchBar">
                    <form class="search" id="searchForm" method="post">
                        <input type="text" name="search" id="searchBar" placeholder="Search Appointment">
                        <input type="submit" name="searchButton" id="searchButton" value="Search">
                    </form>
                    <div class="sort">
                        <form id="filterForm" method="post">
                            <label for="filter">Sort By: </label>
                            <select name="filter" id="filter">
                                <option value="" hidden selected>Default</option>
                                <option value="1">By Name Ascending (A-Z)</option>
                                <option value="2">By Name Descending (Z-A)</option>
                                <option value="3">Newest Appointment</option>
                                <option value="4">Oldest Appointment</option>
                                <option value="5">Active Appointments</option>
                                <option value="6">Expired Appointments</option>
                                <option value="7">Completed Appointments</option>
                            </select>
                        </form>
                    </div>
                    <button type="submit" id="reset"><i class='bx bx-revision'></i></button>
                </div>
                <div class="list">
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>General Medicine (OPD)</td>
                            <td>March 29, 2025</td>
                            <td>9:00 AM</td>
                            <td class="status"><span class="statusText">Expired</span></td>
                            <td>March 25, 2025</td>
                        </tr>
                    </table>
                </div>
                <div class="buttons" id="buttons">
                    <button class="changeStatus" id="changeStatus"><p>Change Status</p></button>
                    <button class="delete" id="delete"><p>Cancel Appointment</p></button>
                </div>
            </div>
            <button class="printButton" id="printAppointmentsBtn"><i class='bx bxs-printer'></i><p>Print Appointment List</p></button>
        </div>
        <div class="status-modal-overlay" id="statusModalOverlay">
            <div class="status-modal">
                <div class="statusIcon"><i class='bx bx-info-circle'></i></div>
                <p id="statusMessage">This is a sample message</p>
                <div class="modal-buttons">
                    <button id="confirmYes">Yes</button>
                    <button id="confirmNo">No</button>
                    <button id="okBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
