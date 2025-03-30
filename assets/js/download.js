document.addEventListener('click', function (event) {
    let targetClass = null;
    let excludeClass = null;

    if (event.target.id === 'downloadPrescriptionBtn') {
        targetClass = '.prescriptionInformation';
    } else if (event.target.id === 'printUnpaidBillsBtn') {
        targetClass = '.unpaidBills';
    } else if (event.target.id === 'printPaidBillsBtn') {
        targetClass = '.paidBills';
    } else if (event.target.id === 'printPatientsBtn') {
        targetClass = '.patientsList';
        excludeClass = '.searchBar'; // Exclude search bar
    } else if (event.target.id === 'printAppointmentsBtn') {
        targetClass = '.allAppointments';
        excludeClass = '.searchBar';
    }

    if (targetClass) {
        const element = document.querySelector(targetClass);
        if (!element) return;

        // Clone the element and apply a print-specific class
        const clone = element.cloneNode(true);
        clone.classList.add('printContainer');

        // Remove the excluded element (search bar)
        if (excludeClass) {
            const excludeElement = clone.querySelector(excludeClass);
            if (excludeElement) excludeElement.remove();
        }

        // Create a temporary print-only container
        const printContainer = document.createElement('div');
        printContainer.appendChild(clone);
        document.body.appendChild(printContainer);

        window.print();

        printContainer.remove();
    }
});
