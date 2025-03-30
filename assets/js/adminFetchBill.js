document.addEventListener("DOMContentLoaded", function () {
    fetch("../process/getAdminTotalBill.php") // Update with the correct path
        .then(response => response.json())
        .then(data => {
            const unpaidTable = document.querySelector(".unpaidBills .list table");
            const paidTable = document.querySelector(".paidBills .list table");

            // Clear existing rows (except headers)
            unpaidTable.innerHTML = `
                <tr>
                    <th>Name</th>
                    <th>Bill (₱)</th>
                    <th>Payment Status</th>
                    <th>Date</th>
                </tr>
            `;
            paidTable.innerHTML = `
                <tr>
                    <th>Name</th>
                    <th>Bill (₱)</th>
                    <th>Payment Status</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                </tr>
            `;

            // Populate unpaid bills
            if (data.unpaid.length === 0) {
                unpaidTable.innerHTML += `
                    <tr>
                        <td colspan="4">No unpaid bills.</td>
                    </tr>
                `;
            } else {
                data.unpaid.forEach(bill => {
                    const formattedDate = new Date(bill.date).toLocaleDateString('en-US', { 
                        year: 'numeric', month: 'long', day: 'numeric' 
                    });

                    unpaidTable.innerHTML += `
                        <tr>
                            <td>${bill.name}</td>
                            <td>${bill.total_amount}</td>
                            <td><span style='color:red; font-weight:700;'>Unpaid</span></td>
                            <td>${formattedDate}</td>
                        </tr>
                    `;
                });
            }

            // Populate paid bills
            if (data.paid.length === 0) {
                paidTable.innerHTML += `
                    <tr>
                        <td colspan="5">No paid bills.</td>
                    </tr>
                `;
            } else {
                data.paid.forEach(bill => {
                    const formattedDate = new Date(bill.date).toLocaleDateString('en-US', { 
                        year: 'numeric', month: 'long', day: 'numeric' 
                    });

                    paidTable.innerHTML += `
                        <tr>
                            <td>${bill.name}</td>
                            <td>${bill.total_amount}</td>
                            <td><span style='color:Green; font-weight:700;'>Paid</span></td>
                            <td>${bill.payment_method}</td>
                            <td>${formattedDate}</td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => console.error("Error fetching billing data:", error));
});
