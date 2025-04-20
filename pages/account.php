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
        <?php include '../includes/patientSidebar.php' ?>
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
                        <p class="userName">Name</p>
                        <p class="userID">User ID</p>
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

    <div class="account-modal-overlay" id="accountPasswordOverlay" style="display: none;">
        <div class="account-modal">
            <p class="account-modal-text">Enter your password to save changes:</p>
            <input type="password" id="accountPasswordInput" placeholder="Password">
                <div id="passwordError" class="error-message" style="display:hidden;"></div>
                <div id="emailError" class="error-message" style="display:hidden;"></div>
            <div class="account-modal-buttons">
            <button id="confirmAccountPasswordBtn">Confirm</button>
            <button id="cancelAccountPasswordBtn">Cancel</button>
            </div>
        </div>
    </div>

    <div class="account-modal-overlay" id="accountSuccessOverlay" style="display: none;">
        <div class="account-modal">
            <div class="account-modal-icon"><i class='bx bx-check-circle'></i></div>
            <p class="account-modal-text">User information changed successfully.</p>
            <div class="account-modal-buttons">
                <button id="confirmAccountSuccessBtn">Ok</button>
            </div>
        </div>
    </div>

    <div class="account-modal-overlay" id="changePasswordOverlay" style="display: none;">
        <div class="account-modal">
            <div class="account-modal-icon"><i class='bx bx-lock-alt'></i></div>
            <p class="account-modal-text">Change your password</p>

            <div class="form-group">
            <input type="password" id="currentPassword" placeholder="Current Password" />
            <div id="currentPasswordError" class="error-message"></div>
            </div>

            <div class="form-group">
            <input type="password" id="newPassword" placeholder="New Password" />
            <div id="newPasswordError" class="error-message"></div>
            </div>

            <div class="form-group">
            <input type="password" id="confirmNewPassword" placeholder="Confirm New Password" />
            <div id="confirmNewPasswordError" class="error-message"></div>
            </div>

            <div id="passwordError" class="error-message"></div>

            <div class="account-modal-buttons">
            <button id="confirmPasswordChangeBtn">Save</button>
            <button id="cancelPasswordChangeBtn">Cancel</button>
            </div>
        </div>
    </div>
</body>
</html>