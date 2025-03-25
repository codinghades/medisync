function updateDashboardSummary() {
    fetch("../process/getDashboardSummary.php")
        .then(response => response.text())
        .then(data => {
            const lines = data.split("\n");
            document.getElementById("appointmentCard").querySelector(".cardInfo").textContent = lines[0].replace("Appointment: ", "") || "None";
            document.getElementById("prescriptionCard").querySelector(".cardInfo").textContent = lines[1].replace("Prescription: ", "") || "None";
            document.getElementById("billingCard").querySelector(".cardInfo").textContent = lines[2].replace("Bills: ", "") || "None";
        })
        .catch(() => {
            document.querySelectorAll(".cardInfo").forEach(card => {
                card.textContent = "Error loading data";
            });
        });

    // Fetch notifications
    fetch("../process/notification.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                fetchNotifications(); // Refresh displayed notifications
            }
        })
        .catch(error => console.error("Error fetching notifications:", error));
}

document.addEventListener("DOMContentLoaded", function() {
    updateDashboardSummary();
    fetchNotifications(); // Initial fetch for notifications
});

setInterval(updateDashboardSummary, 10000);
setInterval(fetchNotifications, 10000); // Update notifications every 10 seconds
