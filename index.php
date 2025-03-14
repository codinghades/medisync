<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management Login</title>
    <script defer src="assets/js/script.js"></script>
</head>
<body>

    <div id="loginPanel">
        <h2>Login</h2>
        <form id="loginForm">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="#" onclick="togglePanel('registerPanel')">Register</a> | <a href="#" onclick="togglePanel('forgotPanel')">Forgot Password?</a></p>
    </div>

    <div id="registerPanel" style="display: none;">
        <h2>Register</h2>
        <form id="registerForm">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p><a href="#" onclick="togglePanel('loginPanel')">Back to Login</a></p>
    </div>

    <div id="forgotPanel" style="display: none;">
        <h2>Forgot Password</h2>
        <form id="forgotForm">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Reset Password</button>
        </form>
        <p><a href="#" onclick="togglePanel('loginPanel')">Back to Login</a></p>
    </div>

</body>
</html>