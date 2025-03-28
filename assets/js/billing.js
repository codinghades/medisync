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
        <div class='header'><p class='header'>Unpaid Bills</p></div>`;
    container.innerHTML += title;
    if (bills.length === 0) {
        container.innerHTML += '<p>No unpaid bills.</p>';
    } else {
        bills.forEach(bill => {
            const amount = parseFloat(bill.Amount);
            if (isNaN(amount)) {
                console.error('Invalid amount for bill:', bill);
                return;
            }
            const billElement = `
                <div class="bill">
                    <dl>
                        <dt>
                            <div class="name">${bill.ConsultationType} Fee</div>
                            <div class="amount">₱${amount.toFixed(2)}</div>
                        </dt>
                        <dd>
                            <div class="date">Date: ${new Date(bill.ConsultationDate).toLocaleDateString()}</div>
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
    
    const payButton = `
        <button class="payNowButton" id="payNowButton">Pay Now</button>`;
    container.innerHTML += payButton;

    document.getElementById('payNowButton').addEventListener('click', function() {
        showPaymentModal();
    });

    if (total == 0){
        document.getElementById('payNowButton').style.display = 'none';
    }
}

function showPaymentModal() {
    const modal = document.getElementById('paymentModal');
    modal.style.display = 'flex';

    document.getElementById('payWithCash').addEventListener('click', function() {
        document.getElementById('paymentMessage').innerText = 'Please proceed to the cashier for payment. Thank you!';
        closeModalAfterDelay(modal, "Cash");
    });
    
    document.getElementById('payWithCard').addEventListener('click', function() {
        document.getElementById('paymentMessage').innerText = 'You will be redirected to your bank payment system. Thank you!';
        closeModalAfterDelay(modal, "Card");
    });
    
    function closeModalAfterDelay(modal, method) {
        setTimeout(function() {
            markAllBillsAsPaid(method);
            modal.style.display = 'none';
        }, 3000);
    }
}    

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('paymentModal');
    if (event.target === modal) {
        modal.style.display = 'none'; // Hide the modal
    }
}

function markAllBillsAsPaid(paymentMethod) {
    const totalAmount = document.querySelector(".totalAmount span").textContent.replace("₱", "").trim();

    fetch("../process/updatePaymentStatus.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ totalAmount, paymentMethod }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(data.message);
            location.reload();
        } else {
            console.error("Error:", data.error);
        }
    })
    .catch(error => console.error("Error:", error));
}

function displayPaidBills(bills) {
    const container = document.querySelector('.paidBills');
    container.innerHTML = ''

    const title = `
    <div class='header'><p class='header'>Paid Bills</p></div>`;
    container.innerHTML += title;

    if (bills.length === 0) {
        container.innerHTML += '<p>No paid bills.</p>';
    } else {
        bills.forEach(bill => {
            const amount = parseFloat(bill.Amount);
            if (isNaN(amount)) {
                console.error('Invalid amount for bill:', bill);
                return;
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