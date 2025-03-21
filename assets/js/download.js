document.addEventListener('DOMContentLoaded', function () {
    const downloadButton = document.getElementById('downloadPrescriptionBtn');
    if (downloadButton) {
        downloadButton.addEventListener('click', function () {
            const element = document.querySelector('.prescriptionInformation'); // The content to be downloaded as PDF

            // Use html2pdf to generate the PDF
            html2pdf()
                .from(element) // The HTML element you want to convert to PDF
                .save('Prescription.pdf'); // Save the file as 'Prescription.pdf'
        });
    }
});
