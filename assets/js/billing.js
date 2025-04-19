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
    const wrapper = document.querySelector('.unpaidWrapper');
    const totalAmountElement = document.querySelector('.totalAmount span');
    wrapper.innerHTML = ''; // Clear only the bill list
    let total = 0;

    if (bills.length === 0) {
        wrapper.innerHTML += '<p>No unpaid bills.</p>';
    } else {
        bills.forEach(bill => {
            const amount = parseFloat(bill.Amount);
            if (isNaN(amount)) return;

            wrapper.innerHTML += `
                <div class="bill">
                    <dl>
                        <dt>
                            <div class="name">${bill.ConsultationType} Fee</div>
                            <div class="amount">₱${amount.toFixed(2)}</div>
                        </dt>
                        <dd>
                            <div class="date">Date: ${new Date(bill.created_at).toLocaleDateString()}</div>
                            <div class="status unpaid">Pending</div>
                        </dd>
                    </dl>
                </div>`;
            total += amount;
        });
    }

    totalAmountElement.textContent = `₱${total.toFixed(2)}`;

    let payBtn = document.querySelector("#payNowButton");
    if (payBtn) payBtn.remove();

    if (total > 0) {
        const payButton = document.createElement('button');
        payButton.className = 'payNowButton';
        payButton.id = 'payNowButton';
        payButton.textContent = 'Pay All';
        document.querySelector('.unpaidBills').appendChild(payButton);
        payButton.addEventListener('click', showPaymentModal);
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
    const container = document.querySelector('.paidWrapper');
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
                            <div class="date">Paid: ${new Date(bill.created_at).toLocaleDateString()}</div>
                            <div class="status paid">Completed</div>
                        </dd>
                    </dl>
                </div>`;
            container.innerHTML += billElement;
        });
    }
}