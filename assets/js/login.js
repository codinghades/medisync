document.addEventListener("DOMContentLoaded", function () {
    // Check if the user is already logged in
    fetch("../process/checkLogin.php")
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                window.location.href = data.redirect;
            }
        });

    function showNotification(message, type) {
        const notification = document.getElementById("notification");
        const messageBox = document.getElementById("notificationMessage");

        messageBox.textContent = message;
        notification.classList.remove("hidden", "success", "error");
        notification.classList.add(type);

        setTimeout(() => {
            notification.classList.add("hidden");
        }, 3000);
    }

    document.getElementById("loginForm").addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch("../auth/login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(role => {
            role = role.trim();
            if (role === "patient") {
                showNotification("Login Successful!", "success");
                setTimeout(() => {
                    window.location.href = "../pages/dashboard.php";
                }, 2000);
            } else if (role === "admin") {
                showNotification("Login Successful!", "success");
                setTimeout(() => {
                    window.location.href = "../admin/dashboard.php";
                }, 2000);
            } else {
                showNotification(role, "error");
            }
        })
        .catch(() => {
            showNotification("An error occurred. Please try again.", "error");
        });
    });
});
