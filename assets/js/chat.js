document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.querySelector('.chat-box');
    const messageHistory = document.querySelector('.message-history');
    const sendButton = document.getElementById('sendButton');
    const messageInput = document.querySelector('textarea');
    const chatBubble = document.querySelector('.chat-bubble');
    const form = document.querySelector('form');

    const userId = "<?= $_SESSION['user_id']; ?>"; // Dynamically add the session user ID to JS

    // Function to fetch messages
    function loadMessages() {
        fetch('../process/getMessage.php', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            messageHistory.innerHTML = '';  // Clear previous messages
            if (data.messages) {
                data.messages.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('message');

                    // Check if the sender is the user (patient) or admin using the ID prefix
                    if (message.sender.startsWith('P-')) {
                        messageElement.classList.add('user');  // Patient message (right side)
                    } else {
                        messageElement.classList.add('admin');  // Admin message (left side)
                    }

                    messageElement.innerHTML = `
                        <strong>${message.sender_name}</strong><br> <!-- Ensure sender_name is used here -->
                        ${message.message}<br>
                        <div class="messageDate">Sent at: ${message.timestamp}</div>
                    `;
                    messageHistory.appendChild(messageElement);
                });
                messageHistory.scrollTop = messageHistory.scrollHeight;  // Scroll to the bottom
            } else {
                const noMessages = document.createElement('div');
                noMessages.classList.add('message');
                noMessages.innerText = 'No messages found.';
                messageHistory.appendChild(noMessages);
            }
        })
        .catch(error => {
            console.error('Error fetching messages:', error);
        });
    }

    // Load messages when the chat box is opened
    chatBubble.addEventListener('click', () => {
        chatBox.style.display = 'flex';  // Show chat box
        loadMessages();  // Fetch and display messages
    });

    // Send new message
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (message) {
            fetch('../process/sendMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message: message
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageInput.value = '';  // Clear input field
                    loadMessages();  // Reload messages
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        }
    });

    // Close chat box
    document.querySelector('.close-btn').addEventListener('click', () => {
        chatBox.style.display = 'none';
    });
});
