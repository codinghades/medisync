document.addEventListener("DOMContentLoaded", function () {
    const prescriptionContainer = document.querySelector(".mainContent");

    // Function to load prescriptions
    function loadPrescriptions() {
        fetch("../process/getPrescriptions.php")
            .then(response => response.text())
            .then(data => {
                prescriptionContainer.innerHTML = '<div class="pageTitle"><p>Prescriptions</p></div>' + data;
            })
            .catch(() => {
                console.error("Failed to fetch prescription data.");
                prescriptionContainer.innerHTML = "<p>No active prescriptions</p>";
            });
    }

    // Initial load of prescriptions
    loadPrescriptions();

    // Optionally, reload prescriptions every 5 seconds (just like the appointments)
    setInterval(loadPrescriptions, 5000);
});
