document.addEventListener('click', function (event) {
    if (event.target && event.target.id === 'downloadPrescriptionBtn') {
        const element = document.querySelector('.prescriptionInformation');

        // Clone the element to avoid modifying the original
        const clonedElement = element.cloneNode(true);
        clonedElement.style.display = 'block';

        // Temporarily replace the page content with the prescription for printing
        const originalContent = document.body.innerHTML;
        document.body.innerHTML = clonedElement.outerHTML;

        // Print the document
        window.print();

        // Restore original content
        document.body.innerHTML = originalContent;

        // Download as PDF
        html2pdf().from(element).save('Prescription.pdf');
    }
});
