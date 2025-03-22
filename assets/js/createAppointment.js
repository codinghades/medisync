document.addEventListener("DOMContentLoaded", function () {
    const appointmentForm = document.getElementById("appointmentForm");

    if (appointmentForm) {
        appointmentForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent page reload

            const formData = new FormData(this); // Gather form data

            fetch("../process/createAppointment.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text(); // Parse response as text
            })
            .then(message => {
                alert(message); // Show response message
                this.reset(); // Clear form after submission
                loadAppointments(); // Reload appointments after submission
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred. Please try again.");
            });
        });
    }

    function loadAppointments() {
        fetch("../process/getAppointments.php")
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text(); // Parse response as text
            })
            .then(data => {
                document.querySelector(".appointmentLists").innerHTML = "<p>Appointment History</p>" + data;
            })
            .catch(error => {
                console.error("Failed to fetch appointment history:", error);
            });
    }

    loadAppointments(); // Initial load of appointments
    setInterval(loadAppointments, 5000); // Reload appointments every 5 seconds
});