<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescriptions</title>
    <link rel="stylesheet" href="../assets/css/patientPrescriptions.css">
    <script src="../assets/js/prescription.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script src="../assets/js/download.js"></script>
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/patientSidebar.php' ?>
        </div>
        <div class="mainContent">
            <div class="pageTitle"><p>Prescriptions</p></div>
            <div class="activePrescription">
                <p class="title">Active Prescription</p>
                <div class="prescriptionInformation">
                    <div class="header">
                        <div class="left">
                            <div class="doctorName">
                                <p>Dr. Lorem Ipsum</p>
                            </div>
                            <div class="doctorIDContainer">
                                <p>Doctor ID:</p>
                                <p class="doctorID">D-2025-001</p>
                            </div>
                            <div class="doctorNumberContainer">
                                <p>Mobile:</p>
                                <p class="doctorNumber">09121231234</p>
                            </div>
                        </div>
                        <div class="right">
                            <img src="../assets/images/Medisync Logo.png" alt="Medisync Logo">
                        </div>
                    </div>
                    <div class="date">
                        <p>March 21, 2025</p>
                    </div>
                    <div class="main">
                        <div class="patientInformation">
                            <div class="info">
                                <p class="label">Patient Name:</p>
                                <p class="patientName">John Doe</p>
                            </div>
                            <div class="info">
                                <p class="label">Age:</p>
                                <p class="patientAge">30</p>
                            </div>
                        </div>

                        <div class="medication">
                            <table>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Dosage</th>
                                    <th>Duration</th>
                                    <th>Instruction</th>
                                </tr>
                                <tr>
                                    <td>Paracetamol</td>
                                    <td>500mg</td>
                                    <td>3 days</td>
                                    <td>Take after meals</td>
                                </tr>
                            </table>
                            <hr>
                            <div class="advice">
                                <p class="label">Advice Given:</p>
                                <p class="adviceText">No chicken for 5 days</p>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</body>
</html>