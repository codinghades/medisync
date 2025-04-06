document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.querySelector('.chat-box');
    const messageHistory = document.querySelector('.message-history');
    const sendButton = document.getElementById('sendButton');
    const messageInput = document.querySelector('textarea');
    const chatBubble = document.querySelector('.chat-bubble');
    const form = document.querySelector('form');

    const userId = "<?= $_SESSION['user_id']; ?>"; // Set user ID if needed later
    let currentSessionId = null; // To store the active chat session ID

    // Function to load messages for the current session
    function loadMessages(sessionId) {
        const url = sessionId ? `../process/getMessage.php?session_id=${sessionId}` : '../process/getMessage.php';
        fetch(url)
            .then(response => response.json())
            .then(data => {
                messageHistory.innerHTML = ''; // Clear old messages

                if (data.messages) {
                    data.messages.forEach(message => {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message');

                        // Align based on sender ID prefix
                        if (message.sender && message.sender.startsWith('P-')) {
                            messageElement.classList.add('user');
                        } else if (message.sender) {
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

    // Function to create a new chat session if one doesn't exist and return the session ID
    async function getOrCreateSession() {
        if (currentSessionId) {
            return currentSessionId; // Return existing session if already set
        }

        return fetch('../process/getOrCreateChatSession.php') // Corrected filename
            .then(response => response.json())
            .then(data => {
                if (data.session_id) {
                    currentSessionId = data.session_id;
                    return currentSessionId;
                } else {
                    console.error('Failed to get or create session:', data.error);
                    return null;
                }
            })
            .catch(error => {
                console.error('Error getting or creating session:', error);
                return null;
            });
    }

    // Send message
    async function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        const sessionId = await getOrCreateSession();
        if (!sessionId) return; // Don't send if session creation failed

        fetch('../process/sendMessage.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ session_id: sessionId, message: message }) // Include session_id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadMessages(sessionId); // Reload messages for the current session
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
    chatBubble.addEventListener('click', async () => {
        chatBox.style.display = 'flex';
        await getOrCreateSession(); // Ensure session exists when chat is opened
        loadMessages(currentSessionId);
    });

    // Close chat
    document.querySelector('.close-btn').addEventListener('click', () => {
        chatBox.style.display = 'none';
    });

    // Initially try to get or create a session when the page loads (optional, depending on your flow)
    getOrCreateSession().then(sessionId => {
        if (sessionId) {
            loadMessages(sessionId);
        }
    });

    // Send message on Enter (Shift+Enter allows newline)
    messageInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
});