document.addEventListener("DOMContentLoaded", function () {
    const prescriptionContainer = document.querySelector(".mainContent");

    function loadPrescriptions() {
        fetch("../process/getPrescriptions.php")
            .then(response => response.text())
            .then(data => {
                prescriptionContainer.innerHTML = '<div class="titleContainer"><div class="title">Prescriptions</div><div class="subTitle">View Prescriptions</div></div>' + data;
            })
            .catch(() => {
                console.error("Failed to fetch prescription data.");
                prescriptionContainer.innerHTML = "<p>No active prescriptions</p>";
            });
    }

    loadPrescriptions();
    setInterval(loadPrescriptions, 5000);
});
