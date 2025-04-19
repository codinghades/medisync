document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".activeAppointments");
    const changeStatusBtn = document.getElementById("changeStatus");
    const deleteBtn = document.getElementById("delete");
    let user_id;

    function fetchAppointments() {
        fetch("../process/adminActiveAppointments.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
        })
        .then(response => response.json())
        .then(data => updateTable(data))
        .catch(error => console.error("Error fetching appointments:", error));
    }

    function updateTable(data) {
        container.innerHTML = `
            <div class="header">
                <p>Active Appointments</p>
            </div>
            <div class="wrapper">
                <div class="list">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        `;

        const tbody = container.querySelector("tbody");

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6">No appointments found.</td></tr>`;
            return;
        }

        data.forEach(appointment => {
            let statusClass = appointment.status.trim().toLowerCase() === "active" ? "status-active" :
                              appointment.status.trim().toLowerCase() === "expired" ? "status-expired" :
                              "status-completed";

            const row = `
                <tr>
                    <td>${appointment.patient_name}</td>
                    <td>${appointment.appointment_type}</td>
                    <td>${appointment.appointment_date}</td>
                    <td>${appointment.appointment_time}</td>
                    <td class="status"><span class="${statusClass}">${appointment.status}</span></td>
                    <td>${appointment.created_at}</td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    fetchAppointments();
});
