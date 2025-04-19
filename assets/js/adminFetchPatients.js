document.addEventListener("DOMContentLoaded", function () {
    fetch("../process/adminPatients.php")
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector(".list table tbody");

            tbody.innerHTML = "";

            if (!data || data.length === 0) {
                tbody.innerHTML = `
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

                const unpaidBill = parseFloat(patient.unpaid_bill) >= 1
                    ? `<span style='color:red; font-weight:700;'>₱${patient.unpaid_bill}</span>`
                    : "<span style='color:gray; font-weight:700;'>None</span>";

                const appointment = patient.closest_appointment !== "None"
                    ? `<span style='font-weight:700;'>${new Date(patient.closest_appointment).toLocaleString('en-US', {
                        month: 'long', day: 'numeric', year: 'numeric',
                        hour: '2-digit', minute: '2-digit', hour12: true
                    })}</span>`
                    : "<span style='color:gray; font-weight:700;'>None</span>";

                const row = `
                    <tr>    
                        <td>${patient.name}</td>
                        <td>${formattedDate}</td>
                        <td>${patient.active_prescription === "Yes" ? "<span style='color:green; font-weight:700;'>Active</span>" : "<span style='color:gray; font-weight:700;'>None</span>"}</td>
                        <td>${unpaidBill}</td>
                        <td>${appointment}</td>
                    </tr>
                `;

                tbody.innerHTML += row;
            });
        })
        .catch(error => console.error("Error fetching patient data:", error));
});
