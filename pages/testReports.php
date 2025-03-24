<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Results</title>
    <link rel="stylesheet" href="../assets/css/patientTestReports.css">
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/patientSidebar.php' ?>
        </div>
        <div class="mainContent">
            <div class="pageTitle"><p>Test Results</p></div>

            <div class="testResultsContainer">
                <!-- Recent Test Result -->
                <div class="recentTest">
                    <p class="title">Recent Test Result</p>
                    <p class="testType">Blood Test</p>  <!-- New line added for clarity -->
                    
                    <div class="testInformation">
                        <div class="header">
                            <div class="left">
                                <p><span class="label">Patient Name:</span> Anthony Mautog</p>
                                <p><span class="label">Patient ID:</span> 123456</p>
                                <p><span class="label">Date of Test:</span> March 17, 2025</p>
                            </div>
                            <div class="right">
                                <img src="../assets/images/Medisync Logo.png" alt="Medisync Logo">
                            </div>
                        </div>
                        <hr>

                        <h2 class="subTitle">Test Details</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Test Name</th>
                                    <th>Result</th>
                                    <th>Normal Range</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Blood Sugar</td>
                                    <td>110 mg/dL</td>
                                    <td>70-100 mg/dL</td>
                                    <td class="highlight">Slightly High</td>
                                </tr>
                                <tr>
                                    <td>Cholesterol</td>
                                    <td>180 mg/dL</td>
                                    <td>< 200 mg/dL</td>
                                    <td>Normal</td>
                                </tr>
                                <tr>
                                    <td>Hemoglobin</td>
                                    <td>13.5 g/dL</td>
                                    <td>12-16 g/dL</td>
                                    <td>Normal</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="footer"><p>-</p></div>

                        <div class="downloadReportButton">
                            <button type="button">Download Report (PDF)</button>
                        </div>
                    </div>
                </div>

                <!-- Reports History -->
                <div class="reportsHistory">
                    <p class="title">Reports History</p>
                    <div class="report">
                        <dl>
                            <dt>
                                <div class="name">Blood Test</div>
                                <div class="date">March 10, 2025</div>
                            </dt>
                            <dd>
                                <div class="info">Results available for download.</div>
                            </dd>
                        </dl>
                    </div>
                    <div class="report">
                        <dl>
                            <dt>
                                <div class="name">X-Ray</div>
                                <div class="date">February 28, 2025</div>
                            </dt>
                            <dd>
                                <div class="info">No abnormalities detected.</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
