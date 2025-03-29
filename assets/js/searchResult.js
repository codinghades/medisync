document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("searchForm");
    const searchBar = document.getElementById("searchBar");
    const reset = document.getElementById("reset");
    const sortSelect = document.getElementById("filter"); // Dropdown for sorting
    const table = document.querySelector(".list table");

    // Reload page on reset button click
    reset.addEventListener("click", function () {
        location.reload();
    });

    // Fetch and update patient data based on search
    searchForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevents page refresh

        let query = searchBar.value.trim();
        if (query.length === 0) return;

        fetch("../process/searchPatient.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ search: query })
        })
        .then(response => response.json())
        .then(data => updateTable(data))
        .catch(error => console.error("Error fetching patient data:", error));
    });

    // Fetch and update patient data based on sorting
    filter.addEventListener("change", function () {
        let sortOption = sortSelect.value;
        fetch(`../process/filterPatient.php?`, {
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
                <th>Register Date</th>
                <th>Prescription Status</th>
                <th>Unpaid Bill</th>
                <th>Appointment</th>
            </tr>
        `;

        if (data.error || data.length === 0) {
            table.innerHTML += `
                <tr><td colspan="5">No results found.</td></tr>
            `;
            return;
        }

        data.forEach(patient => {
            const formattedDate = new Date(patient.registration_date).toLocaleDateString('en-US', {
                year: 'numeric', month: 'long', day: 'numeric'
            });

            table.innerHTML += `
                <tr>    
                    <td>${patient.name}</td>
                    <td>${formattedDate}</td>
                    <td>${patient.active_prescription === "Yes" ? "<span style='color:green; font-weight:700;'>Active</span>" : "None"}</td>
                    <td>${patient.unpaid_bill === "Yes" ? "<span style='color:red; font-weight:700;'>Has Unpaid Bill</span>" : "None"}</td>
                    <td>${patient.has_appointment === "Yes" ? "<span style='color:green; font-weight:700;'>Active</span>" : "None"}</td>
                </tr>
            `;
        });
    }
});
