function updateDashboardSummary() {
    fetch("../process/getDashboardSummary.php")
        .then(response => response.text())
        .then(data => {
            const lines = data.split("\n");
            document.getElementById("appointmentCard").querySelector(".cardInfo").textContent = lines[0].replace("Appointment: ", "") || "None";
            document.getElementById("prescriptionCard").querySelector(".cardInfo").textContent = lines[1].replace("Prescription: ", "") || "None";
            // document.getElementById("billingCard").querySelector(".cardInfo").textContent = lines[2].replace("Bills: ", "") || "None";
        })
        .catch(() => {
            document.querySelectorAll(".cardInfo").forEach(card => {
                card.textContent = "Error loading data";
            });
        });
}

// Load data when page loads
document.addEventListener("DOMContentLoaded", updateDashboardSummary);
setInterval(updateDashboardSummary, 10000);
