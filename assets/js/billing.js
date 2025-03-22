document.addEventListener('DOMContentLoaded', function() {
    fetchBills();
});

function fetchBills() {
    fetch('../process/getBills.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            displayUnpaidBills(data.unpaid);
            displayPaidBills(data.paid);
        })
        .catch(error => console.error('Error fetching bills:', error));
}

function displayUnpaidBills(bills) {
    const container = document.querySelector('.unpaidBills');
    const totalAmountElement = document.querySelector('.totalAmount span');
    container.innerHTML = ''; // Clear previous content
    let total = 0;
    const title = `
        <p class="title">Unpaid Bills</p>`;
    container.innerHTML += title;
    if (bills.length === 0) {
        container.innerHTML += '<p>No unpaid bills.</p>';
    } else {
        bills.forEach(bill => {
            const amount = parseFloat(bill.Amount); // Ensure Amount is a number
            if (isNaN(amount)) {
                console.error('Invalid amount for bill:', bill); // Log if amount is invalid
                return; // Skip this bill if the amount is invalid
            }
            const billElement = `
                <div class="bill">
                    <dl>
                        <dt>
                            <div class="name">${bill.ConsultationType} Fee</div>
                            <div class="amount">₱${amount.toFixed(2)}</div>
                        </dt>
                        <dd>
                            <div class="date">Due: ${new Date(bill.ConsultationDate).toLocaleDateString()}</div>
                            <div class="status unpaid">Pending</div>
                        </dd>
                    </dl>
                </div>`;
            container.innerHTML += billElement;
            total += amount; // Add to total
        });
    }

   
    const totalAmount = `
        <div class="totalAmount">
            <strong>Total: </strong> <span>₱${total.toFixed(2)}</span>
        </div>`;
    container.innerHTML += totalAmount;

    // Add a single Pay Now button below the total amount
    const payButton = `
        <button class="payNowButton" id="payNowButton">Pay Now</button>`;
    container.innerHTML += payButton;

    // Add event listener to the Pay Now button
    document.getElementById('payNowButton').addEventListener('click', function() {
        showPaymentModal();
    });
}

function showPaymentModal() {
    const modal = document.getElementById('paymentModal');
    modal.style.display = 'flex'; // Show the modal

    // Add event listeners for payment method buttons
    document.getElementById('payWithCash').addEventListener('click', function() {
        document.getElementById('paymentMessage').innerText = 'Please proceed to the cashier for payment.';
        closeModalAfterDelay(modal); // Close modal after 3 seconds
    });

    document.getElementById('payWithCard').addEventListener('click', function() {
        document.getElementById('paymentMessage').innerText = 'You will be redirected to your bank payment system.';
        closeModalAfterDelay(modal); // Close modal after 3 seconds
    });

    // Close modal functionality
    document.getElementById('closeModal').addEventListener('click', function() {
        modal.style.display = 'none'; // Hide the modal
    });
}

// Function to close the modal after a delay
function closeModalAfterDelay(modal) {
    setTimeout(function() {
        markAllBillsAsPaid()
        modal.style.display = 'none'; // Hide the modal
    }, 3000); // 3000 milliseconds = 3 seconds
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('paymentModal');
    if (event.target === modal) {
        modal.style.display = 'none'; // Hide the modal
    }
}

function markAllBillsAsPaid() {
    fetch('../process/updatePaymentStatus.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(data.message);
            location.reload()
            // Optionally refresh the bills display
        } else {
            console.error('Error:', data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function displayPaidBills(bills) {
    const container = document.querySelector('.paidBills');
    container.innerHTML = ''; // Clear previous content

    const title = `
    <p class="title">Billing History</p>`;
    container.innerHTML += title;

    if (bills.length === 0) {
        container.innerHTML += '<p>No paid bills.</p>';
    } else {
        bills.forEach(bill => {
            const amount = parseFloat(bill.Amount); // Ensure Amount is a number
            if (isNaN(amount)) {
                console.error('Invalid amount for bill:', bill); // Log if amount is invalid
                return; // Skip this bill if the amount is invalid
            }
            const billElement = `
                <div class="bill">
                    <dl>
                        <dt>
                            <div class="name">${bill.ConsultationType} Fee</div>
                            <div class="amount">₱${amount.toFixed(2)}</div>
                        </dt>
                        <dd>
                            <div class="date">Paid: ${new Date(bill.ConsultationDate).toLocaleDateString()}</div>
                            <div class="status paid">Completed</div>
                        </dd>
                    </dl>
                </div>`;
            container.innerHTML += billElement;
        });
    }
}