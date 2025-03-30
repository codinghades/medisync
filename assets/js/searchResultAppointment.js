document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("searchForm");
    const searchBar = document.getElementById("searchBar");
    const reset = document.getElementById("reset");
    const sortSelect = document.getElementById("filter");
    const table = document.querySelector(".allAppointments .list table");

    // Reload page on reset button click
    reset.addEventListener("click", function () {
        location.reload();
    });

    // Fetch and update appointment data based on search
    searchForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevents page refresh

        let query = searchBar.value.trim();
        if (query.length === 0) return;

        fetch("../process/searchPatientAppointment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ search: query })
        })
        .then(response => response.json())
        .then(data => updateTable(data))
        .catch(error => console.error("Error fetching appointment data:", error));
    });

    // Fetch and update appointment data based on sorting
    sortSelect.addEventListener("change", function () {
        let sortOption = sortSelect.value;
        fetch("../process/filterPatientAppointment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ filter: sortOption })
        })
        .then(response => response.json())
        .then(data => updateTable(data))
        .catch(error => console.error("Error fetching sorted data:", error));
    });

    // Function to update the table with new data
    function updateTable(data) {
        table.innerHTML = `
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        `;

        if (data.error || data.length === 0) {
            table.innerHTML += `
                <tr><td colspan="6">No results found.</td></tr>
            `;
            return;
        }

        data.forEach(appointment => {
            const formattedDate = new Date(appointment.appointment_date).toLocaleDateString('en-US', {
                year: 'numeric', month: 'long', day: 'numeric'
            });
        
            const createdAt = new Date(appointment.created_at).toLocaleDateString('en-US', {
                year: 'numeric', month: 'long', day: 'numeric'
            });
        
            let statusClass = appointment.status.trim().toLowerCase() === "active" ? "status-active" :
                              appointment.status.trim().toLowerCase() === "expired" ? "status-expired" :
                              "status-completed"; // Default for "Completed"
        
            table.innerHTML += `
                <tr>    
                    <td>${appointment.patient_name}</td>
                    <td>${appointment.appointment_type}</td>
                    <td>${formattedDate}</td>
                    <td>${appointment.appointment_time}</td>
                    <td class="status">
                        <span class="statusText">
                            <span class="${statusClass}">${appointment.status}</span>
                        </span>
                    </td>
                    <td>${createdAt}</td>
                </tr>
            `;
        });        
    }
});
