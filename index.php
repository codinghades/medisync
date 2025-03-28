<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medisync Login</title>
    <script defer src="assets/js/togglePanel.js"></script>

    <script defer src="assets/js/login.js"></script>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <div class="mainContainer">
        <div id="notification" class="notification hidden">
            <p id="notificationMessage" class="notificationMessage"></p>
        </div>

        <div class="panel" id="loginPanel">
            <img src="assets/images/Medisync Logo.png" alt="MediSync Logo">
            <form id="loginForm" method="post" class="loginForm">
                <input type="email" name="email" id="loginEmailInput" placeholder="Enter Email" required>
                <input type="password" name="password" id="loginPasswordInput" placeholder="Enter Password" required>
                <input type="submit" value="Log In" name="login" id="loginButton">
            </form>
            <p>New user? <a href="#" onclick="togglePanel('registerPanel')">Sign Up</a></p>
        </div>

        <div class="panel hidden" id="registerPanel">
            <img src="assets/images/Medisync Logo.png" alt="MediSync Logo">
            <form id="registerForm" method="post" class="registerForm">
                <input type="text" name="firstName" id="firstNameInput" placeholder="First Name" required>
                <input type="text" name="lastName" id="lastNameInput" placeholder="Last Name" required>

                <select name="gender" id="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>

                <input type="tel" name="contactNumber" id="contactNumberInput" placeholder="Contact Number" pattern="[0-9]{11}" required>
                <input type="email" name="email" id="registerEmailInput" placeholder="Enter Email" required>
                <input type="password" name="password" id="registerPasswordInput" placeholder="Enter Password" required>
                <input type="password" name="confirmPassword" id="confirmPasswordInput" placeholder="Confirm Password" required>
                
                <input type="submit" value="Sign Up" name="signup" id="signupButton">
            </form>
            <p>Already have an account? <a href="#" onclick="togglePanel('loginPanel')">Log In</a></p>
        </div>
    </div>

    <script defer src="assets/js/register.js"></script>
</body>
</html>
