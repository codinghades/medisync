<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Download</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
</head>
<body>
    <div class="contentToExport">
        <h1>Test Content</h1>
        <p>This content will be converted to PDF.</p>
    </div>
    <button id="downloadTestBtn">Download PDF</button>

    <script>
        document.getElementById('downloadTestBtn').addEventListener('click', function () {
            const element = document.querySelector('.contentToExport');
            html2pdf()
                .from(element)
                .save('TestDownload.pdf');
        });
    </script>
</body>
</html>
