<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medisync Login</title>
</head>
<body>
    <div class="mainContainer">
        <div class="panel" id="loginPanel">
            <img src="assets/images/Medisync Logo.png" alt="MediSync Logo">
            <form action="auth/login.php" method="post" class="loginForm">
                <input type="email" name="email" id="emailInput" placeholder="Enter Email" required>
                <input type="password" name="password" id="passwordInput" placeholder="Enter Password" required>
                <input type="submit" value="Log In" name="login" id="loginButton">
            </form>
            <p>New user? <a href="">Sign Up</a></p>
        </div>
        <div class="panel" id="registerPanel">
        <img src="assets/images/Medisync Logo.png" alt="MediSync Logo">
            <form action="auth/register.php" method="post" class="registerForm">
                <input type="text" name="firstName" id="firstNameInput" placeholder="First Name" required>
                <input type="text" name="lastName" id="lastNameInput" placeholder="Last Name" required>
                <label><input type="radio" name="gender" id="genderInput" required>Male</label>
                <label><input type="radio" name="gender" id="genderInput" requried>Female</label>
                <input type="tel" name="contactNumber" id="contactNumberInput" placeholder="Contact Nubmer" pattern="[0-9]{11}" required>
                <input type="email" name="email" id="emailInput" placeholder="Enter Email" required>
                <input type="password" name="password" id="passwordInput" placeholder="Enter Password" required>
                <input type="password" name="confirmPassword" id="confirmPasswordInput" placeholder="Confirm Password" required>
                <input type="submit" value="Sign Up" name="signup" id="signupButton">
            </form>
            <p>Already have an account? <a href="">Log In</a></p>
        </div>
</body>
</html>