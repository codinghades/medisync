document.addEventListener("DOMContentLoaded", function () {
    const allTable = document.querySelector(".allAppointments .list table");
    function fetchAppointments() {
        fetch("../process/adminAppointments.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
        })
        .then(response => response.json())
        .then(data => updateTable(data))
        .catch(error => console.error("Error fetching appointments:", error));
    }

    function updateTable(data) {
        // Table header
        allTable.innerHTML = `
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        `;

        if (data.length === 0) {
            allTable.innerHTML += `<tr><td colspan="6">No appointments found.</td></tr>`;
            return;
        }

        data.forEach(appointment => {
            let statusClass = appointment.status === "Active" ? "Active" : "Expired";

            allTable.innerHTML += `
                <tr>
                    <td>${appointment.patient_name}</td>
                    <td>${appointment.appointment_type}</td>
                    <td>${appointment.appointment_date}</td>
                    <td>${appointment.appointment_time}</td>
                    <td class="status"><span class="statusText ${statusClass}">${appointment.status}</span></td>
                    <td>${appointment.created_at}</td>
                </tr>
            `;
        });
    }

    fetchAppointments();
});
