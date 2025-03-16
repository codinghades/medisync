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

    document.getElementById("registerForm").addEventListener("submit", function (event) {
        event.preventDefault();

        const firstName = document.getElementById("firstNameInput").value.trim();
        const lastName = document.getElementById("lastNameInput").value.trim();
        const gender = document.getElementById("gender").value;
        const contactNumber = document.getElementById("contactNumberInput").value.trim();
        const email = document.getElementById("registerEmailInput").value.trim();
        const password = document.getElementById("registerPasswordInput").value;
        const confirmPassword = document.getElementById("confirmPasswordInput").value;

        if (password !== confirmPassword) {
            showNotification("Passwords do not match!", "error");
            return;
        }

        const phoneRegex = /^[0-9]{11}$/;
        if (!phoneRegex.test(contactNumber)) {
            showNotification("Invalid contact number! Must be 11 digits.", "error");
            return;
        }

        checkEmailExists(email, function (exists) {
            if (exists) {
                showNotification("Email is already registered!", "error");
            } else {
                submitRegistrationForm();
            }
        });
    });

    function checkEmailExists(email, callback) {
        const formData = new FormData(document.getElementById("registerForm"));
        fetch("../auth/checkEmail.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            callback(data.trim() === "exists");
        })
        .catch(() => {
            showNotification("An error occurred. Please try again.", "error");
        });
    }

    function submitRegistrationForm() {
        const formData = new FormData(document.getElementById("registerForm"));

        fetch("../auth/register.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                showNotification("Registration Successful!", "success");
                document.getElementById("registerForm").reset();

                setTimeout(() => {
                    togglePanel("loginPanel");
                }, 3500);
            } else {
                showNotification(data, "error");
            }
        })
        .catch(() => {
            showNotification("An error occurred. Please try again.", "error");
        });
    }
});
