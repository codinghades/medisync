<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/account.css">
    <title>Account</title>
    <script src="../assets/js/account.js"></script>
</head>
<body>
    <div class="mainContainer"></div>
    <div class="sidebar">
        <?php include '../includes/adminSidebar.php' ?>
    </div>
    <div class="mainContent">
        <div class="titleContainer">
            <div class="title">Account</div>
            <div class="subTitle">Edit and Manage your Account</div>
        </div>
        <div class="cardContainer">
            <div class="card">
                <div class="header">
                    <div class="name">
                        <p class="userName">Juan Dela Cruz</p>
                        <p class="userID">P-2025-0000</p>
                    </div>
                    <div class="headerLogo"><img src="../assets/images/Medisync Logo.png" alt="Medisync Logo"></div>
                </div>
                <div class="main">
                    <div class="form">
                        <form method="POST" class="form">
                            <input type="text" name="firstName" id="firstNameInput" placeholder="First Name" value="" disabled>
                            <input type="text" name="lastName" id="lastNameInput" placeholder="Last Name" value="" disabled>
                            <input type="email" name="email" id="emailInput" placeholder="Email" value="" disabled>
                            <input type="tel" name="contactNumber" id="contactNumberInput" placeholder="Contact Number" value="" disabled>
                            <input type="submit" name="changePassword" id="changePasswordButton" value="Change Password">
                            <div class="editButton">
                                <input type="submit" name="edit" id="editButton" value="Edit">
                            </div>
                            <div class="saveCancelButtons" style='display:none';>
                                <input type="submit" name="save" id="saveButton" value="Save">
                                <input type="submit" name="cancel" id="cancelButton" value="Cancel">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="footer"></div>
            </div>
        </div>
    </div>
</body>
</html>