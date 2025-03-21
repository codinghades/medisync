document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("appointmentForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent page reload

        const formData = new FormData(this);

        fetch("../process/createAppointment.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(message => {
            alert(message); // Show response message
            this.reset(); // Clear form after submission
        })
        .catch(() => {
            alert("An error occurred. Please try again.");
        });
    });
});
