document.addEventListener("DOMContentLoaded", function () {
    const appointmentForm = document.getElementById("appointmentForm");
    const appointmentList = document.querySelector(".appointmentLists");

    const appointmentOverlay = document.getElementById('appointmentOverlay');
    const confirmAppointmentButton = document.getElementById('confirmAppointment');
    const cancelAppointmentButton = document.getElementById('cancelAppointment');
    const appointmentExistsOverlay = document.getElementById('appointmentExistsOverlay');
    const confirmExistingAppointmentButton = appointmentExistsOverlay.querySelector('#confirmAppointment'); // Assuming the 'Ok' button in the new modal has this ID

    if (cancelAppointmentButton) {
        cancelAppointmentButton.addEventListener('click', function () {
            appointmentOverlay.style.display = 'none';
        });
    }

    function autofillUserName() {
        fetch("../process/getUser.php")
            .then(response => response.json())
            .then(user => {
                if (user && user.firstName && user.lastName && user.contactNumber) {
                    const firstNameInput = document.querySelector('input[name="firstName"]');
                    const lastNameInput = document.querySelector('input[name="lastName"]');
                    const contactNumberInput = document.querySelector('input[name="contactNumber"]');
                    if (firstNameInput) firstNameInput.value = user.firstName;
                    if (lastNameInput) lastNameInput.value = user.lastName;
                    if (contactNumberInput) contactNumberInput.value = user.contactNumber;
                }
            })
            .catch(error => {
                console.error("Failed to fetch user data:", error);
            });
    }

    autofillUserName();

    const confirmAppointmentHandler = function () {
        const formData = new FormData(appointmentForm);

        fetch("../process/createAppointment.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(message => {
            appointmentOverlay.style.display = 'none';
        
            if (message.includes("already have an active appointment") || message.includes("in the past")) {
                const messageText = appointmentExistsOverlay.querySelector(".message-text");
                if (messageText) messageText.textContent = message;
                appointmentExistsOverlay.style.display = 'flex';
            } else if (message.toLowerCase().includes("success")) {
                showMessage(message);
                appointmentForm.reset();
                loadAppointments();
                autofillUserName();
            } else {
                showMessage("An unexpected response occurred. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showMessage("An error occurred. Please try again.");
        });
    };

    if (confirmAppointmentButton) {
        confirmAppointmentButton.addEventListener('click', confirmAppointmentHandler);
    }

    if (appointmentForm) {
        appointmentForm.addEventListener("submit", function (event) {
            event.preventDefault();

            appointmentOverlay.style.display = 'flex';

            if (confirmAppointmentButton) {
                confirmAppointmentButton.removeEventListener('click', confirmAppointmentHandler);
                confirmAppointmentButton.addEventListener('click', confirmAppointmentHandler);
            }
        });
    }

    function showMessage(message) {
        const successOverlay = document.getElementById("appointmentSuccessOverlay");
        const successMessage = document.getElementById("appointmentSuccessMessage");
        const closeBtn = document.getElementById("closeSuccessModal");
    
        if (successOverlay && successMessage && closeBtn) {
            successMessage.textContent = message;
            successOverlay.style.display = "flex";
    
            closeBtn.addEventListener("click", function () {
                successOverlay.style.display = "none";
            }, { once: true }); // prevent multiple bindings
        }
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

    if (confirmExistingAppointmentButton) {
        confirmExistingAppointmentButton.addEventListener('click', function () {
            appointmentExistsOverlay.style.display = 'none';
        });
    }
});