document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("searchForm");
    const searchBar = document.getElementById("searchBar");
    const reset = document.getElementById("reset");
    const sortSelect = document.getElementById("filter");
    const table = document.querySelector(".list table");
    let searchTimeout; // Variable to hold the timeout

    // Initial fetch (load all patients on page load)
    fetchPatients();

    // Reload page on reset button click
    reset.addEventListener("click", function () {
        location.reload();
    });

    // Fetch and update patient data based on search while typing
    searchBar.addEventListener("input", function () {
        clearTimeout(searchTimeout); // Clear any existing timeout

        searchTimeout = setTimeout(() => {
            let query = searchBar.value.trim();
            fetchPatients(query, sortSelect.value);
        }, 300); // Wait 300ms after typing to fetch
    });

    // Fetch and update patient data based on sorting
    sortSelect.addEventListener("change", function () {
        fetchPatients(searchBar.value.trim(), sortSelect.value);
    });

    // Function to fetch patient data with optional search & sorting
    function fetchPatients(search = "", filter = "") {
        fetch("../process/searchPatient.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ search: search, filter: filter })
        })
        .then(response => response.json())
        .then(data => updateTable(data))
        .catch(error => console.error("Error fetching patient data:", error));
    }

    // Function to update the table with patient data
    function updateTable(data) {
        table.innerHTML = `
            <tr>
                <th>Name</th>
                <th>Register Date</th>
                <th>Prescription Status</th>
                <th>Unpaid Bill</th>
                <th>Appointment</th>
            </tr>
        `;

        if (!Array.isArray(data) || data.length === 0) {
            table.innerHTML += `<tr><td colspan="5">No results found.</td></tr>`;
            return;
        }

        data.forEach(patient => {
            const formattedDate = patient.registration_date
                ? new Date(patient.registration_date).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric'
                })
                : "Unknown";

            const unpaidBill = parseFloat(patient.unpaid_bill) > 0
                ? `<span style='color:red; font-weight:700;'>₱${patient.unpaid_bill}</span>`
                : "<span style='color:gray; font-weight:700;'>None</span>";

            const appointment = patient.closest_appointment !== "None"
                ? `<span style='font-weight:700;'>${patient.closest_appointment}</span>`
                : "<span style='color:gray; font-weight:700;'>None</span>";

            table.innerHTML += `
                <tr>
                    <td>${patient.name}</td>
                    <td>${formattedDate}</td>
                    <td>${patient.active_prescription === "Yes"
                        ? "<span style='color:green; font-weight:700;'>Active</span>"
                        : "<span style='color:gray; font-weight:700;'>None</span>"}</td>
                    <td>${unpaidBill}</td>
                    <td>${appointment}</td>
                </tr>
            `;
        });
    }

    // Prevent default form submission (still useful if JS fails or for accessibility)
    searchForm.addEventListener("submit", function (event) {
        event.preventDefault();
        // The 'input' event listener will handle the search on typing
    });
});