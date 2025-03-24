function updateDashboardSummary() {
    fetch("../process/getDashboardSummary.php")
        .then(response => response.text())
        .then(data => {
            document.querySelector(".summary .cardInfo").textContent = data;
        })
        .catch(() => {
            document.querySelector(".summary .cardInfo").textContent = "Error loading data";
        });
}

// Load data when page loads
document.addEventListener("DOMContentLoaded", updateDashboardSummary);

// Refresh every 10 seconds
setInterval(updateDashboardSummary, 10000);
