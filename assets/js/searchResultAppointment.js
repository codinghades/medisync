document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("searchForm");
    const searchBar = document.getElementById("searchBar");
    const reset = document.getElementById("reset");
    const sortSelect = document.getElementById("filter");
    const tbody = document.querySelector(".allAppointments .wrapper .list table tbody");
    let searchTimeout;

    function fetchAppointments() {
        let query = searchBar.value.trim();
        let sortOption = sortSelect.value;

        console.log("Fetching appointments with search query:", query, "and sort option:", sortOption);

        fetch("../process/searchPatientAppointment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ search: query, filter: sortOption })
        })
        .then(response => {
            console.log("Response received:", response);
            return response.json();
        })
        .then(data => {
            console.log("Data received from server:", data);
            updateTable(data);
        })
        .catch(error => {
            console.error("Error fetching appointment data:", error);
        });
    }

    // Update appointments on search input change with a delay
    searchBar.addEventListener("input", function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchAppointments, 300);
    });

    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        fetchAppointments();
    });

    sortSelect.addEventListener("change", function () {
        fetchAppointments();
    });

    reset.addEventListener("click", function () {
        searchBar.value = "";
        sortSelect.value = "";
        fetchAppointments();
    });

    function updateTable(data) {
        console.log("Updating table with data:", data); // Debugging

        tbody.innerHTML = ""; // Clear existing rows first

        if (data.error || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7">No results found.</td></tr>`;
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
                                "status-completed";

            const isActive = appointment.status.trim().toLowerCase() === "active";

            tbody.innerHTML += `
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
                    <td>
                        <input type="checkbox" class="selectCheckbox" name="select" value="${appointment.appointment_id}" ${!isActive ? "disabled" : ""}>
                    </td>
                </tr>
            `;
        });
    }

    // Initial fetch on page load
    fetchAppointments();
});
