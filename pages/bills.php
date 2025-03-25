<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills</title>
    <link rel="stylesheet" href="../assets/css/patientBilling.css">
</head>
<body>
<div class="mainContainer">
        <div class="sidebar">
            <?php include '../includes/patientSidebar.php' ?>
        </div>
        <div class="mainContent">
            <div class="pageTitle"><p>Billing</p></div>

            <div class="billingContainer">
                <!-- Unpaid Bills -->
                <div class="unpaidBills">
                    <p class="title">Unpaid Bills</p>
                    <div class="bill">
                        <dl>
                            <dt>
                                <div class="name">Consultation Fee</div>
                                <div class="amount">₱500.00</div>
                            </dt>
                            <dd>
                                <div class="date">Due: March 25, 2025</div>
                                <div class="status unpaid">Pending</div>
                            </dd>
                        </dl>
                    </div>
                    <div class="payNowButton">
                        <button type="button">Pay Now</button>
                    </div>
                </div>

                <!-- Paid Bills -->
                <div class="paidBills">
                    <p class="title">Billing History</p>
                    <div class="bill">
                        <dl>
                            <dt>
                                <div class="name">Laboratory Test</div>
                                <div class="amount">₱1,200.00</div>
                            </dt>
                            <dd>
                                <div class="date">Paid: March 15, 2025</div>
                                <div class="status paid">Completed</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>