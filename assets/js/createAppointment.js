document.addEventListener("DOMContentLoaded", function () {
    const appointmentForm = document.getElementById("appointmentForm");
    const appointmentList = document.querySelector(".appointmentLists");

    // Confirmation modal elements
    const appointmentOverlay = document.getElementById('appointmentOverlay');
    const confirmAppointmentButton = document.getElementById('confirmAppointment');
    const cancelAppointmentButton = document.getElementById('cancelAppointment');

    // Add event listener for cancel button (outside of the form submission)
    cancelAppointmentButton.addEventListener('click', function () {
        appointmentOverlay.style.display = 'none';
    });

    // Add event listener for confirm button (only once)
    const confirmAppointmentHandler = function () {
        const formData = new FormData(appointmentForm);

        appointmentOverlay.style.display = 'none';
        fetch("../process/createAppointment.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(message => {
            showMessage(message);
            appointmentForm.reset();
            loadAppointments();
        })
        .catch(error => {
            console.error("Error:", error);
            showMessage("An error occurred. Please try again.");
        });
    };

    confirmAppointmentButton.addEventListener('click', confirmAppointmentHandler, { once: true });

    if (appointmentForm) {
        appointmentForm.addEventListener("submit", function (event) {
            event.preventDefault();

            // Show the confirmation modal instead of an alert
            appointmentOverlay.style.display = 'flex';
        });
    }

    function showMessage(message) {
        const messageModal = document.createElement('div');
        messageModal.classList.add('message-modal');
        messageModal.innerHTML = `
            <div class="message-content">
                <p>${message}</p>
                <button class="close-message">Close</button>
            </div>
        `;
        document.body.appendChild(messageModal);

        messageModal.querySelector('.close-message').addEventListener('click', function () {
            document.body.removeChild(messageModal);
        });
    }

    function renderAppointments(data) {
        let html = `<div class="header"><p>Appointment History</p></div>`;

        if (data.appointments.length === 0) {
            html += `<p class='nothing'>No appointments found.</p>`;
        } else {
            data.appointments.forEach(appt => {
                let color = {
                    active: "green",
                    expired: "red",
                    completed: "black"
                }[appt.status.toLowerCase()] || "gray";

                html += `<div class='appointment'>
                            <dl>
                                <dt>
                                    <span class='name'>Appointment for ${appt.type_full} <span style='color: ${color};'>(${appt.status})</span></span>
                                    <span class='date'>${appt.created_date}</span>
                                </dt>
                                <dd>
                                    <span class='info'>You have booked an appointment for ${appt.type_full} on ${appt.formatted_date} at ${appt.formatted_time}. Please arrive at least 30 minutes early to avoid any issues. Thank you.</span>
                                </dd>
                            </dl>
                         </div>`;
            });
        }

        appointmentList.innerHTML = html;
    }

    function loadAppointments() {
        fetch("../process/getAppointments.php")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    appointmentList.innerHTML = `<p class='nothing'>${data.error}</p>`;
                } else {
                    renderAppointments(data);
                }
            })
            .catch(error => {
                console.error("Failed to fetch appointment history:", error);
                appointmentList.innerHTML = "<p class='nothing'>Failed to load appointments.</p>";
            });
    }

    loadAppointments();
    setInterval(loadAppointments, 5000);
});
