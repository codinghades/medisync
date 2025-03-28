<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="../assets/css/adminPayments.css">
</head>
<body>
    <div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/adminSidebar.php'; ?>
        </div>
        <div class="mainContent">
            <div class="titleContainer">
                <div class="title">Payments</div>
                <div class="subTitle">View Patients' Payments</div>
            </div>
            <div class="unpaidBills">
                <div class="header"><p>Unpaid Bills</p></div>
                <div class="list">
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Total Bill</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                        </tr>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>1000.00</td>
                            <td>Unpaid</td>
                            <td>March 28, 2025</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="paidBills">
                <div class="header"><p>Paid Bills</p></div>
                <div class="list">
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Total Bill</th>
                            <th>Payment Status</th>
                            <th>Payment Scheme</th>
                            <th>Date Paid</th>
                        </tr>
                        <tr>
                            <td>Juan Dela Cruz</td>
                            <td>1000.00</td>
                            <td>Paid</td>
                            <td>Card</td>
                            <td>March 28, 2025</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>