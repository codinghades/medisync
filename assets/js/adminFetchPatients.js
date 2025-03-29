document.addEventListener("DOMContentLoaded", function () {
    fetch("../process/adminPatients.php")
        .then(response => response.json())
        .then(data => {
            const table = document.querySelector(".list table");

            // Clear existing rows (except headers)
            table.innerHTML = `
                <tr>
                    <th>Name</th>
                    <th>Register Date</th>
                    <th>Prescription Status</th>
                    <th>Unpaid Bill</th>
                    <th>Appointment</th>
                </tr>
            `;

            if (data.length === 0) {
                table.innerHTML += `
                    <tr>
                        <td colspan="5">No registered patients.</td>
                    </tr>
                `;
                return;
            }

            data.forEach(patient => {
                const formattedDate = new Date(patient.registration_date).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric'
                });

                const row = `
                    <tr>    
                        <td>${patient.name}</td>
                        <td>${formattedDate}</td>
                        <td>${patient.active_prescription === "Yes" ? "<span style='color:green; font-weight:700;'>Active</span>" : "None"}</td>
                        <td>${patient.unpaid_bill === "Yes" ? "<span style='color:red; font-weight:700;'>Has Unpaid Bill</span>" : "None"}</td>
                        <td>${patient.has_appointment === "Yes" ? "<span style='color:green; font-weight:700;'>Active</span>" : "None"}</td>
                    </tr>
                `;
                table.innerHTML += row;
            });
        })
        .catch(error => console.error("Error fetching patient data:", error));
});
