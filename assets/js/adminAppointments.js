document.addEventListener("DOMContentLoaded", function () {
    let shouldReload = false;
    const allTable = document.querySelector(".allAppointments .list table");
    const changeStatusBtn = document.getElementById("changeStatus");
    const deleteBtn = document.getElementById("delete");
    const statusModalOverlay = document.getElementById("statusModalOverlay");
    const confirmYes = document.getElementById("confirmYes");
    const confirmNo = document.getElementById("confirmNo");
    const okBtn = document.getElementById("okBtn");

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
        const tbody = document.querySelector(".allAppointments .wrapper .list table tbody");
        
        tbody.innerHTML = "";
    
        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7">No appointments found.</td></tr>`;
            return;
        }

        data.forEach(appointment => {
            const status = appointment.status.trim().toLowerCase();
            const isActive = status === "active";
            const statusClass = isActive ? "status-active" :
                    status === "expired" ? "status-expired" :
                    status === "cancelled" ? "status-cancelled" :
                    "status-completed";
    
            const row = `
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
    
            tbody.innerHTML += row;
        });
    }
    

    function getSelectedAppointments() {
        return [...document.querySelectorAll("input[name='select']:checked")].map(cb => cb.value);
    }

    function showModal(message, showYesNo = false, reload = false) {
        const statusMessage = document.getElementById("statusMessage");
        statusMessage.textContent = message;
        shouldReload = reload;
    
        confirmYes.style.display = showYesNo ? "inline-block" : "none";
        confirmNo.style.display = showYesNo ? "inline-block" : "none";
        okBtn.style.display = showYesNo ? "none" : "inline-block";
    
        statusModalOverlay.style.display = "flex";
    }
    

    function closeModal() {
        statusModalOverlay.style.display = "none";
    }

    function updateStatus() {
        const selectedAppointments = getSelectedAppointments();
        if (selectedAppointments.length === 0) {
            showModal("Select at least one appointment!", false);
            return;
        }

        showModal("Are you sure you want to change the status of the selected appointment(s)?", true);
        confirmYes.onclick = () => {
            selectedAppointments.forEach((id, index) => {
                fetch("../process/updateAppointmentStatus.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ appointment_id: id })
                })
                .then(response => response.json())
                .then(data => {
                    const isLast = index === selectedAppointments.length - 1;
                    showModal(`${data.message}`, false, isLast);
                })
                .catch(error => {
                    showModal("Error updating appointment status!", false);
                    console.error("Error updating appointment:", error);
                });
            });
            closeModal();
        };

        confirmNo.onclick = closeModal;
    }

    function deleteAppointments() {
        let selectedAppointments = getSelectedAppointments();
        if (selectedAppointments.length === 0) {
            showModal("Select at least one appointment!", false);
            return;
        }

        showModal("Are you sure you want to cancel the selected appointment(s)?", true);
        confirmYes.onclick = () => {
            fetch("../process/deleteAppointments.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ appointment_ids: selectedAppointments })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showModal(data.error, false);
                } else {
                    showModal(data.message, false, true);
                }
            })
            .catch(error => {
                showModal("Error deleting appointments!", false);
                console.error("Error deleting appointments:", error);
            });
            closeModal();
        };

        confirmNo.onclick = closeModal;
    }

    if (changeStatusBtn) changeStatusBtn.addEventListener("click", updateStatus);
    if (deleteBtn) deleteBtn.addEventListener("click", deleteAppointments);

    okBtn.addEventListener("click", () => {
        closeModal();
        if (shouldReload) {
            location.reload();
        }
    });

    fetchAppointments();
});
