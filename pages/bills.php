<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills</title>
    <link rel="stylesheet" href="../assets/css/patientBilling.css">
    <script src="../assets/js/billing.js" defer></script>
</head>
<body>
<div class="mainContainer">
    <div class="sidebar">
        <?php include '../includes/patientSidebar.php' ?>
    </div>
    <div class="mainContent">
        <div class="titleContainer">
            <div class="title">Bills</div>
            <div class="subTitle">Pay Bills</div>
        </div>

        <div class="billingContainer">
            <div class="unpaidBills">
                <p class="title">Unpaid Bills</p>
                <div class="totalAmount">
                    <strong>Total: </strong> <span>₱0.00</span>
                </div>
            </div>

            <div class="paidBills">
                <p class="title">Billing History</p>
            </div>
        </div>
    </div>
</div>

<div id="paymentModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-button" id="closeModal">&times;</span>
        <h2>Select Payment Method</h2>
        <div class="payButtons">
            <button id="payWithCash"><i class='bx bx-money'></i>Cash</button>
            <button id="payWithCard"><i class='bx bxs-credit-card'></i>Card</button>
        </div>
        <div id="paymentMessage" style="margin-top: 20px;"></div>
    </div>
</div>
</body>
</html>