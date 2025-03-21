<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/patientAppointment.css">
    <title>Appointment</title>
</head>
<body>
    <div class="mainContainer"></div>
    <div class="sidebar">
        <?php include '../includes/patientSidebar.php' ?>
    </div>
    <div class="mainContent">
        <div class="pageTitle">
            <p>Appointments</p>
        </div>
        <div class="createAppointment">
            <p>Create Appointment</p>
            <form class="appointmentForm" id="appointmentForm">
                <div class="left">
                    <input type="text" name="firstName" id="firstNameInput" placeholder="First Name" required>
                    <input type="text" name="lastName" id="lastNameInput" placeholder="Last Name" required>
                    <input type="tel" name="contantNumber" id="contactNumberInput" placeholder="Contact Number" required>
                    <select name="type" id="typeInput" >
                        <option value="" disabled selected>Select Type</option>
                        <option value="laboratory">Laboratory & Diagnostics</option>
                        <option value="opd">General Medicine (OPD)</option>
                        <option value="pedia">Pediatrics</option>
                        <option value="obgyn">OB-GYN</option>
                        <option value="ent">ENT</option>
                    </select>
                    <input type="date" name="date" id="dateInput" required>
                    <input type="time" name="time" id="timeInput" require_once>
                    </div>
                <div class="right">
                    <input type="text" name="details" id="detailsInput" placeholder="Input Details (Optional)">
                    <input type="submit" name="submit" id="submitAppointment">
                </div>

            </form>
        </div>
        <div class="appointmentLists">
            <p>Appointment History</p>
            <div class="appointment">
                <dl>
                    <dt>
                        <span class="name">Sample Name</span>
                        <span class="date">March 20, 2025</span>
                    </dt>
                    <dd>
                        <span class="info">This is a sample appointment. Thank You.</span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</body>
</html>