document.addEventListener("DOMContentLoaded", function () {
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

        const email = document.getElementById("loginEmailInput").value.trim();
        const password = document.getElementById("loginPasswordInput").value.trim();

        const formData = new FormData(this);

        fetch("../auth/login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                showNotification("Login Successful!", "success");
                setTimeout(() => {
                    window.location.href = "../pages/dashboard.php";
                }, 2000);
            } else {
                showNotification("Invalid email or password!", "error");
            }
        })
        .catch(() => {
            showNotification("An error occurred. Please try again.", "error");
        });
    });
});