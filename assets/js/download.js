document.addEventListener('click', function (event) {
    if (event.target?.id === 'downloadPrescriptionBtn') {
        const element = document.querySelector('.prescriptionInformation');
        if (!element) return;

        const originalContent = document.body.innerHTML;
        document.body.innerHTML = element.outerHTML;

        window.print();

        document.body.innerHTML = originalContent;
    }
});
