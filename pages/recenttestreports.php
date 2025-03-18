<?php include '../includes/patientSidebar.php'?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Test Result - Hospital Management System</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" />
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md">
    <h1 class="text-2xl font-bold mb-6">Recent Test Result</h1>

    <div class="mb-4">
      <p><span class="font-semibold">Patient Name:</span> Anthony Mautog</p>
      <p><span class="font-semibold">Patient ID:</span> 123456</p>
      <p><span class="font-semibold">Date of Test:</span> March 17, 2025</p>
    </div>

    <h2 class="text-xl font-semibold mb-4">Test Details</h2>
    <table class="w-full border-collapse border border-gray-300">
      <thead>
        <tr class="bg-gray-200">
          <th class="border border-gray-300 p-2">Test Name</th>
          <th class="border border-gray-300 p-2">Result</th>
          <th class="border border-gray-300 p-2">Normal Range</th>
          <th class="border border-gray-300 p-2">Remarks</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="border border-gray-300 p-2">Blood Sugar</td>
          <td class="border border-gray-300 p-2">110 mg/dL</td>
          <td class="border border-gray-300 p-2">70-100 mg/dL</td>
          <td class="border border-gray-300 p-2">Slightly High</td>
        </tr>
        <tr>
          <td class="border border-gray-300 p-2">Cholesterol</td>
          <td class="border border-gray-300 p-2">180 mg/dL</td>
          <td class="border border-gray-300 p-2">< 200 mg/dL</td>
          <td class="border border-gray-300 p-2">Normal</td>
        </tr>
        <tr>
          <td class="border border-gray-300 p-2">Hemoglobin</td>
          <td class="border border-gray-300 p-2">13.5 g/dL</td>
          <td class="border border-gray-300 p-2">12-16 g/dL</td>
          <td class="border border-gray-300 p-2">Normal</td>
        </tr>
      </tbody>
    </table>

    <button class="mt-6 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Download Report (PDF)</button>
  </div>
</body>
</html>
