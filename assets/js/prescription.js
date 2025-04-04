document.addEventListener("DOMContentLoaded", function () {
    const prescriptionContainer = document.querySelector(".mainContent");

    function renderPrescriptions(data) {
        let html = `<div class="titleContainer">
                        <div class="title">Prescriptions</div>
                        <div class="subTitle">View Prescriptions</div>
                    </div>`;

        // --- Active Prescription ---
        if (data.active.length > 0) {
            const info = data.active[0];
            const prescribedDate = new Date(info.date_prescribed);
            const expiryDate = new Date(info.expiry_date);
            const formattedPrescribed = prescribedDate.toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" });
            const formattedExpiry = expiryDate.toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" });
            const daysLeft = info.days_left;

            html += `<div class='activePrescription'>
                        <div class='header'><p class='header'>Active Prescription</p></div>
                        <div class='prescriptionInformation'>
                            <div class='header'>
                                <div class='left'>
                                    <div class='doctorName'><p>${info.doctor_name}</p></div>
                                    <div class='doctorIDContainer'><p>Doctor ID:</p><p class='doctorID'>${info.doctor_id}</p></div>
                                    <div class='doctorNumberContainer'><p>Mobile:</p><p class='doctorNumber'>${info.doctor_mobile}</p></div>
                                </div>
                                <div class='right'><img src='../assets/images/Medisync Logo.png' alt='Medisync Logo'></div>
                            </div>
                            <div class='date'>
                                <p>Prescribed: ${formattedPrescribed}</p>
                                <p>Expires: ${formattedExpiry}</p>
                            </div>
                            <div class='main'>
                                <div class='patientInformation'>
                                    <div class='info'><p class='label'>Patient Name:</p><p class='patientName'>${info.patient_first_name} ${info.patient_last_name}</p></div>
                                </div>
                                <div class='medication'>
                                    <table>
                                        <tr><th>Medicine</th><th>Dosage</th><th>Duration</th><th>Instruction</th></tr>`;

            data.active.forEach(item => {
                html += `<tr>
                            <td>${item.medicine}</td>
                            <td>${item.dosage}</td>
                            <td>${item.duration}</td>
                            <td>${item.instruction}</td>
                         </tr>`;
            });

            const uniqueAdvice = [...new Set(data.active.map(item => item.advice))];

            html += `       </table><hr>
                            <div class='advice'><p class='label'>Advice Given:</p>`;
            uniqueAdvice.forEach(advice => {
                html += `<p class='adviceText'>${advice}</p>`;
            });

            html += `       </div>
                            </div>
                        </div>
                        <div class='footer'><p>-</p></div>
                    </div>`;
        } else {
            html += `<div class='activePrescription'>
                        <p class='title'>Active Prescription</p>
                        <p>No active prescriptions in the last 7 days.</p>
                     </div>`;
        }
        html += `</div>
                <div class='downloadPrescriptionButton'>
                    <button id='downloadPrescriptionBtn'><i class='bx bxs-printer'></i> Print Prescription</button>
                </div>`;
        // --- Prescription History ---
        html += `<div class='prescriptionList'>
                    <p class='title'>Prescription History</p>`;

        if (data.history.length > 0) {
            data.history.forEach(item => {
                const historyDate = new Date(item.date_prescribed).toLocaleDateString("en-US", {
                    year: "numeric", month: "long", day: "numeric"
                });
                const meds = item.medicines.length > 0 ? item.medicines.join(", ") : "No medicines listed.";

                html += `<div class='prescriptionHistory'>
                            <dl>
                                <dt>
                                    <span class='name'>Prescription given by ${item.doctor_name}</span>
                                    <span class='date'>${historyDate}</span>
                                </dt>
                                <dd>
                                    <span class='info'>Based on your consultation, you were prescribed the following medicines: ${meds}.</span>
                                </dd>
                            </dl>
                         </div>`;
            });
        } else {
            html += `<div class='prescriptionHistory'><p class='nothing'>No recent prescriptions</p></div>`;
        }

        prescriptionContainer.innerHTML = html;
    }

    function loadPrescriptions() {
        fetch("../process/getPrescriptions.php")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    prescriptionContainer.innerHTML = `<p>${data.error}</p>`;
                } else {
                    renderPrescriptions(data);
                }
            })
            .catch(() => {
                console.error("Failed to fetch prescription data.");
                prescriptionContainer.innerHTML = "<p>Could not load prescriptions.</p>";
            });
    }

    loadPrescriptions();
    setInterval(loadPrescriptions, 5000);
});
