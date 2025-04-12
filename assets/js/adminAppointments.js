document.addEventListener("DOMContentLoaded", function () {
    const allTable = document.querySelector(".allAppointments .list table");
    const changeStatusBtn = document.getElementById("changeStatus");
    const deleteBtn = document.getElementById("delete");

    if (!allTable) {
        console.error("Table element not found. Check if .allAppointments .list table exists in the DOM.");
        return;
    }

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
    
        if (!Array.isArray(data) || data.length === 0) {
            allTable.innerHTML += `<tr><td colspan="7">No appointments found.</td></tr>`;
            return;
        }
    
        data.forEach(appointment => {
            const status = appointment.status.trim().toLowerCase();
            const isActive = status === "active";
            const statusClass = isActive ? "status-active" :
                                status === "expired" ? "status-expired" :
                                "status-completed";
    
            allTable.innerHTML += `
                <tr>
                    <td>${appointment.patient_name}</td>
                    <td>${appointment.appointment_type}</td>
                    <td>${appointment.appointment_date}</td>
                    <td>${appointment.appointment_time}</td>
                    <td class="status">
                        <span class="statusText">
                            <span class="${statusClass}">${appointment.status}</span>
                        </span>
                    </td>
                    <td>${appointment.created_at}</td>
                    <td>
                        <input type="checkbox" class="selectCheckbox" name="select" value="${appointment.appointment_id}" ${!isActive ? "disabled" : ""}>
                    </td>
                </tr>
            `;
        });
    }
    

    function getSelectedAppointments() {
        return [...document.querySelectorAll("input[name='select']:checked")].map(cb => cb.value);
    }

    function updateStatus() {
        const selectedAppointments = getSelectedAppointments();
        if (selectedAppointments.length === 0) {
            alert("Select at least one appointment!");
            return;
        }
    
        selectedAppointments.forEach((id, index) => {
            fetch("../process/updateAppointmentStatus.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ appointment_id: id })
            })
            .then(response => response.json())
            .then(data => {
                alert(`${data.message}`);
    
                if (index === selectedAppointments.length - 1) {
                    fetchAppointments();
                }
            })
            .catch(error => console.error("Error updating appointment:", error));
        });
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

    if (changeStatusBtn) changeStatusBtn.addEventListener("click", updateStatus);
    if (deleteBtn) deleteBtn.addEventListener("click", deleteAppointments);

    fetchAppointments();
});
