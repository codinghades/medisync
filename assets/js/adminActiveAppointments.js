document.addEventListener("DOMContentLoaded", function () {
    const allTable = document.querySelector(".activeAppointments .list table");
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
        allTable.innerHTML = `
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Select</th>
            </tr>
        `;

        if (data.length === 0) {
            allTable.innerHTML += `<tr><td colspan="7">No appointments found.</td></tr>`;
            return;
        }

        data.forEach(appointment => {
            let statusClass = appointment.status.trim().toLowerCase() === "active" ? "status-active" :
            appointment.status.trim().toLowerCase() === "expired" ? "status-expired" :
            "status-completed";
        
            allTable.innerHTML += `
                <tr>
                    <td>${appointment.patient_name}</td>
                    <td>${appointment.appointment_type}</td>
                    <td>${appointment.appointment_date}</td>
                    <td>${appointment.appointment_time}</td>
                    <td class="status"><span class="${statusClass}">${appointment.status}</span></td>
                    <td>${appointment.created_at}</td>
                    <td><input type="checkbox" class="selectCheckbox" name="select" value="${appointment.appointment_id}"></td>
                </tr>
            `;
        });
        
    }

    function getSelectedAppointments() {
        return [...document.querySelectorAll("input[name='select']:checked")].map(cb => cb.value);
    }

    function updateStatus() {
        let selectedAppointments = getSelectedAppointments();
        if (selectedAppointments.length === 0) return alert("Select at least one appointment!");

        fetch("../process/updateAppointmentStatus.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ appointment_ids: selectedAppointments })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            fetchAppointments();
        })
        .catch(error => console.error("Error updating status:", error));
        location.reload();
    }

    function deleteAppointments() {
        let selectedAppointments = getSelectedAppointments();
        if (selectedAppointments.length === 0) return alert("Select at least one appointment!");

        if (!confirm("Are you sure you want to delete the selected appointments?")) return;

        fetch("../process/deleteAppointments.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ appointment_ids: selectedAppointments })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            fetchAppointments();
        })
        .catch(error => console.error("Error deleting appointments:", error));
        location.reload();
    }

    changeStatusBtn.addEventListener("click", updateStatus);
    deleteBtn.addEventListener("click", deleteAppointments);
    
    fetchAppointments();
});
