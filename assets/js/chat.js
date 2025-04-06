document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.querySelector('.chat-box');
    const messageHistory = document.querySelector('.message-history');
    const sendButton = document.getElementById('sendButton');
    const messageInput = document.querySelector('textarea');
    const chatBubble = document.querySelector('.chat-bubble');
    const form = document.querySelector('form');

    const userId = "<?= $_SESSION['user_id']; ?>"; // Set user ID if needed later

    // Send message on Enter (Shift+Enter allows newline)
    messageInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Load messages
    function loadMessages() {
        fetch('../process/getMessage.php')
            .then(response => response.json())
            .then(data => {
                messageHistory.innerHTML = ''; // Clear old messages

                if (data.messages) {
                    data.messages.forEach(message => {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message');

                        // Align based on sender ID prefix
                        if (message.sender.startsWith('P-')) {
                            messageElement.classList.add('user');
                        } else {
                            messageElement.classList.add('admin');
                        }

                        messageElement.innerHTML = `
                            <strong>${message.sender_name}</strong><br>
                            ${message.message}<br>
                            <div class="messageDate">Sent at: ${message.timestamp}</div>
                        `;
                        messageHistory.appendChild(messageElement);
                    });

                    messageHistory.scrollTop = messageHistory.scrollHeight;
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

    // Send message
    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        fetch('../process/sendMessage.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadMessages();
            } else {
                console.error('Message failed:', data.error);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }

    // Handle form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        sendMessage();
    });

    // Open chat
    chatBubble.addEventListener('click', () => {
        chatBox.style.display = 'flex';
        loadMessages();
    });

    // Close chat
    document.querySelector('.close-btn').addEventListener('click', () => {
        chatBox.style.display = 'none';
    });
});
