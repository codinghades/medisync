document.addEventListener("DOMContentLoaded", function () {
    const appointmentForm = document.getElementById("appointmentForm");

    if (appointmentForm) {
        appointmentForm.addEventListener("submit", function (event) {
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
                loadAppointments(); // Reload appointments after submission
            })
            .catch(() => {
                alert("An error occurred. Please try again.");
            });
        });
    }

    function loadAppointments() {
        fetch("../process/getAppointments.php")
            .then(response => response.text())
            .then(data => {
                document.querySelector(".appointmentLists").innerHTML = "<p>Appointment History</p>" + data;
            })
            .catch(() => {
                console.error("Failed to fetch appointment history.");
            });
    }

    loadAppointments();
    setInterval(loadAppointments, 5000);
});
