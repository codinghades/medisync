<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
    <link rel="stylesheet" href="../assets/css/adminPatients.css">
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/adminSidebar.php'; ?>
        </div>
        <div class="mainContent">
            <div class="titleContainer">
                <div class="title">Patients</div>
                <div class="subTitle">Manage Patients list</div>
            </div>
            <div class="patientsList">
                <div class="header">
                    <p>Active Patients</p>
                </div>
                <div class="searchBar">
                    <form class="search" method="post">
                        <input type="text" name="search" id="search" placeholder='Search Patient by "Name"''>
                        <input type="submit" name="searchButton" id="searchButton" value="Search">
                    </form>
                    <div class="sort">
                        <form action="" method="post">
                            <label for="filter">Sort By: </label>
                            <select name="filter" id="filter">
                                <option value="1" hidden selected>Default</option>
                                <option value="1">By Name Ascending (A-Z)</option>
                                <option value="2">By Name Decending (Z-A) </option>
                                <option value="3">Newest Registration</option>
                                <option value="4">Oldest Registration</option>
                                <option value="5">Active Prescription</option>
                                <option value="6">Unpaid Bill</option>
                                <option value="7">Active Appointment</option>
                            </select>
                        </form>
                    </div>
                    <button type="submit"><i class='bx bx-revision'></i></button>
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>