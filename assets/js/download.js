document.addEventListener('click', function (event) {
    let targetClass = null;
    let excludeClass = null;

    if (event.target.id === 'downloadPrescriptionBtn') {
        targetClass = '.activePrescription';
    } else if (event.target.id === 'printUnpaidBillsBtn') {
        targetClass = '.unpaidBills';
    } else if (event.target.id === 'printPaidBillsBtn') {
        targetClass = '.paidBills';
    } else if (event.target.id === 'printPatientsBtn') {
        targetClass = '.patientsList';
        excludeClass = '.searchBar'; // Exclude search bar
    } else if (event.target.id === 'printAppointmentsBtn') {
        targetClass = '.allAppointments';
        excludeClass = '.searchBar'; // Exclude search bar
    }

    if (targetClass) {
        const element = document.querySelector(targetClass);
        if (!element) return;

        // Clone the element to avoid modifying the original
        const clone = element.cloneNode(true);

        // Remove the excluded element (search bar)
        if (excludeClass) {
            const excludeElement = clone.querySelector(excludeClass);
            if (excludeElement) excludeElement.remove();
        }

        // Create a temporary print-only container
        const printContainer = document.createElement('div');
        printContainer.appendChild(clone);

        // Apply styles to ensure a clean print
        printContainer.style.position = 'absolute';
        printContainer.style.top = '0';
        printContainer.style.left = '0';
        printContainer.style.width = '100%';
        printContainer.style.background = 'white';

        // Append to body and hide the rest of the content
        document.body.appendChild(printContainer);
        document.body.style.visibility = 'hidden';
        printContainer.style.visibility = 'visible';

        window.print();

        // Restore the original visibility and remove the print container
        document.body.style.visibility = 'visible';
        printContainer.remove();
    }
});
