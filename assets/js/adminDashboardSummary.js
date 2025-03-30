async function loadUpcomingAppointments() {
    try {
        const response = await fetch('../process/adminDashboardSummary.php');
        const data = await response.json();

        const tableBody = document.querySelector(".upcomingAppointments .list table");
        tableBody.innerHTML = `
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        `;

        if (!data.upcomingAppointments || data.upcomingAppointments.length === 0) {
            tableBody.innerHTML += `<tr><td colspan="6">No upcoming appointments found.</td></tr>`;
            return;
        }

        data.upcomingAppointments.forEach(appointment => {
            const row = `
                <tr>
                    <td>${appointment.patient_name}</td>
                    <td>${appointment.appointment_type}</td>
                    <td>${new Date(appointment.appointment_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</td>
                    <td>${appointment.appointment_time}</td>
                    <td>Active</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

    } catch (error) {
        console.error("Error loading upcoming appointments:", error);
    }
}

async function fetchSummaryData() {
    try {
        let response = await fetch('../process/adminDashboardSummary.php'); 
        let data = await response.json(); 

        document.querySelector("#totalActiveAppointmetns .total").textContent = data.totalActiveAppointments;
        document.querySelector("#totalActivePatients .total").textContent = data.totalActivePatients;
        document.querySelector("#totalAppointmetns .total").textContent = data.totalAppointments;
        document.querySelector("#totalPatients .total").textContent = data.totalPatients;
    } catch (error) {
        console.error("Error fetching summary data:", error);
    }
}

async function fetchUnpaidBillsData() {
    try {
        let response = await fetch('../process/adminDashboardSummary.php'); // Fetch data from API
        let data = await response.json(); // Convert response to JSON

        // Update the unpaid bills section with the fetched data
        document.querySelector(".numberOfUnpaidBills p").textContent = data.totalUnpaidBills;
        document.querySelector(".amountOfUnpaidbills p").textContent = `₱${data.totalUnpaidAmount}`;
    } catch (error) {
        console.error("Error fetching unpaid bills data:", error);
    }
}

async function fetchActivePatientList() {
    try {
        let response = await fetch('../process/adminDashboardSummary.php'); // Fetch data from API
        let data = await response.json(); // Convert response to JSON

        let tableBody = document.querySelector(".activePatientList .list table");

        // Clear existing table rows
        tableBody.innerHTML = `
            <tr>
                <th>Name</th>
                <th>Register Date</th>
                <th>Prescription Status</th>
                <th>Unpaid Bill</th>
                <th>Appointment</th>
            </tr>`;

        if (data.activePatients.length === 0) {
            tableBody.innerHTML += `<tr><td colspan="5">No active patients found.</td></tr>`;
            return;
        }

        // Populate table with active patient data
        data.activePatients.forEach(patient => {
            let row = `
                <tr>
                    <td>${patient.patient_name}</td>
                    <td>${new Date(patient.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</td>
                    <td>${patient.prescription_status}</td>
                    <td>${patient.unpaid_bill}</td>
                    <td>${patient.active_appointment}</td>
                </tr>`;
            tableBody.innerHTML += row;
        });

    } catch (error) {
        console.error("Error fetching active patient list:", error);
    }
}

document.addEventListener("DOMContentLoaded", function(){
    loadUpcomingAppointments();
    fetchSummaryData();
    fetchUnpaidBillsData();
    fetchActivePatientList();
});
