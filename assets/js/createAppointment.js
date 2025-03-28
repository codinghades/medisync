document.addEventListener("DOMContentLoaded", function () {
    const appointmentForm = document.getElementById("appointmentForm");

    if (appointmentForm) {
        appointmentForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch("../process/createAppointment.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text();
            })
            .then(message => {
                alert(message);
                this.reset();
                loadAppointments();
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
                return response.text();
            })
            .then(data => {
                document.querySelector(".appointmentLists").innerHTML = '<div class="header"><p>Appointment History</p></div>' + data;
            })
            .catch(error => {
                console.error("Failed to fetch appointment history:", error);
            });
    }

    loadAppointments();
    setInterval(loadAppointments, 5000);
});