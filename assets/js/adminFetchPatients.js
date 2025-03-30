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
                        <td>${patient.active_prescription === "Yes" ? "<span style='color:green; font-weight:700;'>Active</span>" : "<span style='color:gray; font-weight:700;'>None</span>"}</td>
                        <td>${patient.unpaid_bill > 0 ? `<span style='color:red; font-weight:700;'>₱${patient.unpaid_bill}</span>` : "<span style='color:gray; font-weight:700;'>None</span>"}</td>
                        <td>${patient.closest_appointment !== "None" 
                            ? `<span style='font-weight:700;'>${new Date(patient.closest_appointment).toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true })}</span>` 
                            : "<span style='color:gray; font-weight:700;'>None</span>"}
                        </td>
                    </tr>
                `;
                table.innerHTML += row;
            });
        })
        .catch(error => console.error("Error fetching patient data:", error));
});
