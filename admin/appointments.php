<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="../assets/css/adminAppointment.css">
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
                                <th>Select</th>
                            </tr>
                            <tr>
                                <td>Juan Dela Cruz</td>
                                <td>General Medicine (OPD)</td>
                                <td>March 29, 2025</td>
                                <td>9:00 AM</td>
                                <td class="status"><span class="statusText">Active</span></td>
                                <td>March 25, 2025</td>
                                <td><input type="checkbox" name="select" id="select" value=""></td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="buttons" id="buttons">
                    <button class="changeStatus" id="changeStatus"><p>Change Status</p></button>
                    <button class="delete" id="delete"><p>Delete</p></button>
                </div>
            </div>
            <div class="allAppointments">
                <div class="header">
                    <p>All Appointments</p>
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
            </div>
        </div>
    </div>
</body>
</html>